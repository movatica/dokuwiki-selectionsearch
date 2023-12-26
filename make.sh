#!/bin/bash

set -euo pipefail

cd "${0%/*}" || exit

function build {
	local target=$1
	mkdir -p "$target"

    cp --update LICENSE "$target/"

	pushd src &>/dev/null

	cp --update plugin.info.txt style.css "../$target/"

	if [ "$target" == "debug" ]; then
		find . -type f -iname '*.php' -exec cp --parents --update {} "../$target" \;
	else
		for file in $(find . -type f -iname '*.php'); do
			if [ ! -f "../$target/$file" ] || [ "$file" -nt "../$target/$file" ]; then
				mkdir -p "../$target/$(dirname "$file")"
				php --strip "$file" > "../$target/$file"
			fi
		done
	fi

	popd &>/dev/null

	# lint and minify javascript
	local compilation_level=SIMPLE

	if [ "$target" == "debug" ]; then
		compilation_level=BUNDLE
	fi

	if [ ! -f "$target/script.js" ] || [ "src/script.js" -nt "$target/script.js" ]; then
		echo "Compiling script.js"
		google-closure-compiler --js src/script.js --js_output_file "$target/script.js" \
			--externs "/usr/local/lib/node_modules/google-closure-compiler/contrib/externs/jquery-3.3.js" \
			--externs "build/dokuwiki-externs.js" \
			--compilation_level $compilation_level \
			--language_in STABLE \
			--language_out STABLE \
			--warning_level VERBOSE
	fi

	# precompress javascript to support static asset serving
	gzip --force --keep --verbose "$target/script.js"

	# get release date
	local date;
	date=$(grep -E "^date\s+" "$target/plugin.info.txt" | grep -Eo "[^ \t]+$")

	# pack everything together
	pushd "$target" &>/dev/null

	zip -9 -X --latest-time --filesync --recurse-paths "../dokuwiki_selectionsearch_$target-$date.zip" ./*

	popd &>/dev/null
}

function check {
	find src -type f -iname '*.php' -exec php --syntax-check {} \;
}

function clean {
	rm -rf release
	rm -rf debug
}

case "$1" in
	clean)
		clean
		;;
	check)
		check
		;;
	debug)
		check
		build debug
		;;
	release)
		check
		build release
		;;
	all)
		build debug
		build release
		;;
	*)
		echo $"Usage: $0 all|check|clean|debug|release"
		exit 1
esac

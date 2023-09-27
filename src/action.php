<?php
class action_plugin_selectionsearch extends DokuWiki_Action_Plugin
{
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, 'publish_configuration');
        $controller->register_hook('TPL_CONTENT_DISPLAY', 'BEFORE', $this, 'inject_tooltip_html');
    }

    public function publish_configuration(Doku_Event $event, $param)
    {
        global $JSINFO;
        $JSINFO['selectionsearch_minlength'] = $this->getConf('min_query_length');
    }

    public function inject_tooltip_html(Doku_Event $event, $param)
    {
        global $ACT;
        global $lang;

        $use_on_actions = explode(',', $this->getConf('use_on_actions'));

        if (!in_array($ACT, $use_on_actions)) return;

        // insert tooltip template
        $event->data .= <<<HTML
            <a id="selectionsearch__tt" target="_blank" title="$lang[btn_search]" href="">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAMAAAAolt3jAAAAY1BMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABmaHTeAAAAIXRSTlMHNEEkTD4WPVEtKzcMQ1NATkc4VFJGHDEhJ0Q6RS8uVQBDAjEGAAAAXklEQVR4AS3HVQ4CUQDF0MHdGZfe/a+SNLz+NKeKNSnJCTj2Sn55rl9w+XNiTNLfaWRFHZsZ5cYZtew4xK7cZD7EBmaZFdtz9gO0MtlRWmTSvafH3GpZOulCWwBpxT/nGxX5oR8AJgAAAABJRU5ErkJggg==" alt="$lang[btn_search]">
            </a>
            HTML;
    }
}

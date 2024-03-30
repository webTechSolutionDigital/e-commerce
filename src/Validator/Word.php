<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;


#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Word extends Constraint
{

    public function __construct(
        public string $message = 'Le mot utilisé "{{ Word }}" n\'est pas valide.',
        public array $banWords = [
            'script', 'iframe', 'alert', 'onmouseover', 'onerror', 'onload',
            'javascript', 'src=', 'href=', 'onclick', 'onsubmit', 'onblur',
            'onfocus', 'onchange', 'eval', 'document.cookie', 'document.write',
            'document.location', 'window.location', 'localStorage', 'sessionStorage',
            'xmlHttpRequest', 'fetch', 'cookie', 'php', 'sql', 'http', 'https',
            'ftp', 'telnet', 'ssh', 'shell', 'bash', 'cmd', 'powershell',
            'phpinfo', 'select', 'insert', 'update', 'delete', 'truncate',
            'drop', 'create', 'alter', 'grant', 'revoke', 'execute', 'union',
            'load_file', 'outfile', 'dumpfile', 'system', 'exec', 'passthru',
            'popen', 'proc_open', 'pcntl_exec', 'chown', 'chmod', 'chgrp',
            'readfile', 'file_get_contents', 'fopen', 'fwrite', 'file_put_contents',
            'move_uploaded_file', 'copy', 'unlink', 'rmdir', 'mkdir', 'rename',
            'symlink', 'mail', 'header', 'session_start', 'setcookie', 'session_id',
            'header()', 'setcookie()', 'session_start()', 'document.cookie',
            'window.location', 'meta', 'content', 'refresh', 'redirect', 'refresh:',
            'url=', 'http-equiv', 'onreadystatechange', 'prompt', 'confirm', 'onabort',
            'onbeforeunload', 'onblur', 'oncancel', 'onchange', 'onclick', 'onclose',
            'oncontextmenu', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter',
            'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onfocus',
            'oninput', 'oninvalid', 'onkeydown', 'onkeypress', 'onkeyup', 'onload',
            'onmessage', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover',
            'onmouseup', 'onmousewheel', 'onoffline', 'ononline', 'onpagehide',
            'onpageshow', 'onpaste', 'onpopstate', 'onresize', 'onscroll', 'onsearch',
            'onselect', 'onshow', 'onsubmit', 'ontoggle', 'onunload', 'onwheel'
        ],
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct(null, $groups, $payload);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.03.2018
 * Time: 15:02
 */
$view_plugins=[];

$view_plugins['tagsinput']=[
    "scripts"=>[
        "/assets/js/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js"
    ],
    "stylesheets"=>[
        "/assets/js/plugins/bootstrap-taginput/bootstrap-tagsinput.css"
    ]
];

$view_plugins['photoswipe']=[
    "stylesheets"=>[
        "/assets/plugins/photoswipe/photoswipe.css",
        "/assets/plugins/photoswipe/default-skin/default-skin.css"
    ],
    "scripts"=>[
        "/assets/plugins/photoswipe/photoswipe.js",
        "/assets/plugins/photoswipe/photoswipe-ui-default.min.js"
    ]
];

$view_plugins['fileupload']=[
    "stylesheets"=>[ ],
    "scripts"=>[
        "/assets/js/plugins/fileupload/jquery.ui.widget.js",
        "/assets/js/plugins/fileupload/jquery.iframe-transport.js",
        "/assets/js/plugins/fileupload/jquery.fileupload.js"
    ]
];

$view_plugins['tagit']=[
    "stylesheets"=>[
        '/assets/plugins/tag-it/css/jquery.tagit.css',
        '/assets/plugins/tag-it/css/tagit.ui-zendesk.css'
    ],
    "scripts"=>[
        "/assets/plugins/tag-it/js/tag-it.min.js",
        "/assets/js/src/tagit_dc.js"
    ]
];
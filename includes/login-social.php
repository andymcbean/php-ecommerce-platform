<?php
return 
[
    "base_url"   => "https://dqdev.co.uk/users/",
    "providers"  => [
        "Google"   => [
            "enabled" => true,
            "keys"    => [ "id" => "262258960221-elcn17mo703efo68kdeh35v5oji99ask.apps.googleusercontent.com", "secret" => "f165JZezkZEpCvApgvR-msW3" ],
        ],
        "Facebook" => [
            "enabled"        => true,
            "keys"           => [ "id" => "209765480590396", "secret" => "1b16065968a2b5bb96c0ebd1c0315a63" ],
            "trustForwarded" => false
        ],
    ],
    "debug_mode" => true,
    "debug_file" => "bug.txt",
];
?>
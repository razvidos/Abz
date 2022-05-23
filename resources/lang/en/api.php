<?php
return [
    "User" => [
        "message" => [
            "200" => "New user successfully registered",
            "404" => "The user with the requested identifier does not exist",
            "401" => "The token expired.",
        ],
        "fails" => [
            "404" => "User not found",
            "user_id_IsNotInteger" => "The user_id must be an integer.",
        ],
        "validation" => [
            "unUniqueEmailOrPhone" => "User with this phone or email already exist",
        ],
    ],
    "Positions" => [
        "message" => [
            "404" => "Positions not found",
        ],
    ],
    "Validation" => [
        "failed" => 'Validation failed',
    ],
    "Pagination" => [
        "message" => [
            "404" => "Page not found",
        ],
    ],
];

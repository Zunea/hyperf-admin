<?php
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

$max = 'Cannot exceed: max characters';
$required_input = 'This cannot be empty';
$required_select = 'Please choose';
return [
    'Auth' => [
        'LoginRequest' => [
            'username' => ['required' => 'Please enter your login account', 'alpha_dash' => 'Account format error', 'between' => 'Account format error [:between length]'],
            'password' => ['required' => 'Please enter the login password', 'alpha_dash' => 'Password format error', 'between' => 'Password format error [:between length]']
        ]
    ],
    'User' => [
        'AddRequest' => [
            'username' => ['required' => 'Please enter a user account', 'alpha_dash' => 'Account format error', 'between' => 'Account format error [:between length]'],
            'password' => ['required' => 'Please enter the login password', 'alpha_dash' => 'Password format error', 'between' => 'Password format error [:between length]'],
            'nickname' => ['max' => $max],
            'phone'    => ['required' => 'Please enter the phone number', 'max' => $max],
            'email'    => ['required' => 'Please enter your email account', 'email' => 'Email account format error', 'max' => $max]
        ],
        'UpdateRequest' => [
            'username' => ['required' => 'Please enter a user account', 'alpha_dash' => 'Account format error', 'between' => 'Account format error [:between length]'],
            'password' => ['required' => 'Please enter the login password', 'alpha_dash' => 'Password format error', 'between' => 'Password format error [:between length]'],
            'nickname' => ['max' => $max],
            'phone'    => ['required' => 'Please enter the phone number', 'max' => $max],
            'email'    => ['required' => 'Please enter your email account', 'email' => 'Email account format error', 'max' => $max],
            'status'   => ['required' => 'Please select user status', 'in' => 'The selected user status is not available']
        ]
    ],
    'UserGroup' => [
        'AddRequest' => [
            'name'   => ['required' => 'Please enter a user group name', 'max' => $max],
            'remark' => ['max' => $max]
        ],
        'UpdateRequest' => [
            'name'   => ['required' => 'Please enter a user group nam', 'max' => $max],
            'remark' => ['max' => $max],
            'status' => ['required' => 'Please select user group status', 'in' => 'The selected status is not available']
        ]
    ],
    'Resource' => [
        'ResourceRequest' => [
            'name'   => ['required' => 'Please enter the resource name', 'max' => $max],
            'path'   => ['required' => 'Please enter the resource path', 'max' => $max],
            'method' => ['required' => 'Please select a resource type', 'in' => 'Resource type only accepts [:in]']
        ]
    ],
    'Menu' => [
        'AddRequest' => [
            'name'      => ['required_if' => $required_input, 'alpha_dash' => 'wrong format', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Wrong choice, please reselect'],
            'icon'      => ['alpha_dash' => 'wrong format', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'wrong format', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Wrong choice, please reselect']
        ],
        'UpdateRequest' => [
            'name'      => ['required' => $required_input, 'alpha_dash' => 'wrong format', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Wrong choice, please reselect'],
            'icon'      => ['alpha_dash' => 'wrong format', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'wrong format', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Wrong choice, please reselect'],
            'status'    => ['required' => $required_select, 'in' => 'Wrong choice, please reselect']
        ]
    ],
    'Config' => [
        'ConfigRequest' => [
            'name'   => ['required' => $required_input, 'max' => $max],
            'title'  => ['required' => $required_input, 'max' => $max],
            'group'  => ['required' => $required_input, 'max' => $max],
            'type'   => ['required' => $required_select, 'max' => $max],
            'tips'   => ['max' => $max],
            'format' => ['max' => $max],
        ]
    ]
];
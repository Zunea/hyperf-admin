<?php
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */
$max = '不能超过:max个字符';
$required_input = '此处不能为空';
$required_select = '请选择';

return [
    'Auth' => [
        'LoginRequest' => [
            'username' => ['required' => '请输入登陆账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password' => ['required' => '请输入登陆密码', 'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]']
        ]
    ],
    'User'  => [
        'AddRequest' => [
            'username' => ['required' => '请输入用户账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password' => ['required' => '请输入登陆密码', 'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]'],
            'nickname' => ['max' => '用户昵称长度不能超过:max个字符'],
            'phone'    => ['required' => '请输入手机号码', 'max' => '手机号码长度不能超过:max个字符'],
            'email'    => ['required' => '请输入邮箱账号', 'email' => '邮箱账号格式错误', 'max' => '邮箱账号长度不能超过:max个字符']
        ],
        'UpdateRequest' => [
            'username' => ['required' => '请输入用户账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password' => ['alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]'],
            'nickname' => ['max' => '用户昵称不能超过:max字符'],
            'phone'    => ['required' => '请输入手机号码', 'max' => '手机号码长度不能超过:max个字符'],
            'email'    => ['email' => '邮箱账号格式错误', 'max' => '邮箱账号长度不能超过:max个字符'],
            'status'   => ['required' => '请选择用户状态', 'in' => '所选用户状态不可用']
        ]
    ],
    'UserGroup' => [
        'AddRequest' => [
            'name'   => ['required' => '请输入用户组名称', 'max' => '用户组名称不能超过:max个字符'],
            'remark' => ['max' => '备注不能超过:max个字符']
        ],
        'UpdateRequest' => [
            'name'   => ['required' => '请输入用户组名称', 'max' => '用户组名称不能超过:max个字符'],
            'remark' => ['max' => '备注不能超过:max个字符'],
            'status' => ['required' => '请选择用户组状态', 'in' => '所选用状态不可用']
        ]
    ],
    'Resource' => [
        'ResourceRequest' => [
            'name'   => ['required' => '请输入资源名称', 'max' => '资源名称不能超过:max个字符'],
            'path'   => ['required' => '请输入资源路径', 'max' => '资源路径不能超过:max个字符'],
            'method' => ['required' => '请选择资源类型', 'in' => '资源类型只接受[:in]']
        ]
    ],
    'Menu' => [
        'AddRequest' => [
            'name'      => ['required_if' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'icon'      => ['alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => '选择错误，请重新选择']
        ],
        'UpdateRequest' => [
            'name'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'icon'      => ['alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'status'    => ['required' => $required_select, 'in' => '选择错误，请重新选择']
        ]
    ],
    'Config' => [
        'ConfigRequest' => [
            'name'   => ['required' => $required_input, 'max' => $max],
            'title'  => ['required' => $required_input, 'max' => $max],
            'group'  => ['required' => $required_select, 'max' => $max],
            'type'   => ['required' => $required_select, 'max' => $max],
            'tips'   => ['max' => $max],
            'format' => ['max' => $max],
        ]
    ]
];
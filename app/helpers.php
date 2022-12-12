<?php
function setActive($routeName)
{
	return request()->routeIs($routeName) ? 'active' : '';
	//{{ dump(request()->routeIs('bd.index'))}}
}

function miNombre()
{
	return "dany Navarro";
}

function hideEmail($email){
    $prefix = substr($email, 0, strrpos($email, '@'));
    $suffix = substr($email, strripos($email, '@'));
    $len  = floor(strlen($prefix)/2);

    return substr($prefix, 0, $len) . str_repeat('*', $len) . $suffix;
}

function hideCel($number){
    return substr($number, 0, 3) . '***' . substr($number,  -3);
}

function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
}

function validar_fecha_espanol($fecha){
    $valores = explode('/', $fecha);
    if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
        return true;
    }
    return false;
}
?>
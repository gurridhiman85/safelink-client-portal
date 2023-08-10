<?php
/**
 * Created by PhpStorm.
 * User: Gurpreet Singh
 * Date: 01-08-2023
 * Time: 03:40 PM
 */

namespace App\Helpers;


class Sdwan
{
    public static function getAuthToken($request){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://poc.pilot.multapplied.net/api/v4/login/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'email' => $request['SDWAN']['email'],
                'password' => $request['SDWAN']['password']
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function get_all_spaces($auth_token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://poc.pilot.multapplied.net/api/v4/spaces/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $auth_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function create_new_space($request_dody, $auth_token){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://poc.pilot.multapplied.net/api/v4/spaces/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request_dody, //array('name' => 'test2','key' => 'test2','parent' => 'https://poc.pilot.multapplied.net/api/v4/spaces/bright/')
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $auth_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function space_object_counts($space, $request_dody){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://poc.pilot.multapplied.net/api/v4/spaces/".$space->name."/object_counts/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $request_dody['SDWAN']['email'].':'.$request_dody['SDWAN']['password']);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    public static function get_bonds($request_dody, $space_key){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://poc.pilot.multapplied.net/api/v4/bonds/?search=".$space_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $request_dody['SDWAN']['email'].':'.$request_dody['SDWAN']['password']);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    public static function get_legs($request_dody){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://poc.pilot.multapplied.net/api/v4/legs/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $request_dody['SDWAN']['email'].':'.$request_dody['SDWAN']['password']);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    public static function doSearch($value, array $array) {
        return count(array_filter($array, function($var) use ($value) {
            return $value === $var['space']['key'];
        }));
    }

    public static function create_new_bond($request_dody, $auth_token){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://poc.pilot.multapplied.net/api/v4/bonds/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $request_dody['SDWAN']['email'].':'.$request_dody['SDWAN']['password']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_dody));

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

}

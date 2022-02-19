<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Helper
{
    /**
     * Функция принимает массив и возвращает ответ в виде json 
     *
     * @param array $arr
     * @return Response
     */
    public static function responseJson(array $arr): Response
    {
        $response = new Response();
        $response->setContent(json_encode($arr));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Принимает обьект класса, проходит валидацию и возвращает строку ошибок 
     *
     * @param Object $object
     * @param ValidatorInterface $validator
     * @return string
     */
    public static function validate(Object $object, ValidatorInterface $validator): string
    {
        $errorsString = '';
        $errors = $validator->validate($object);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
        }

        return $errorsString;
    }
}

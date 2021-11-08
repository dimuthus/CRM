<?php
namespace app\components;

class CustomSessionHandler extends \SessionHandler
{
    public function destroy($sessionId)
    {
        die('puka');
        return parent::destroy($sessionId);
    }
}
<?php

namespace App;

// Superglobal.
class Redirect
{


    /**
     * Get Redirect
     *
     * @param $redirectValue value for redirect
     * @return void
     */
    public function getRedirect($redirectValue)
    {
        header('Location: '.$redirectValue.'');
        exit();

    }//end getRedirect()


}//end class

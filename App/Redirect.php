<?php

namespace App;

// Superglobal.
class Redirect
{
    /**
     * Get Redirect
     *
     * @return void
     */
    public function getRedirect($redirectValue)
    {
        header('Location: '.$redirectValue.'');

    }//end getRedirect()


}//end class

<?php

    /**
     * This function renders the HTML file (typically will be index.php).
     * 
     * @param templateName - php file with the html markup
     * @param variables - context variables that will be render into the template
     * @param callback - function to call along with rendering (typically will be SQL query)
     * 
     * @return - output containing the HTML markup, else an error message if no template file is found
     * 
     */
    function render($templateName, $variables=null){

        $extension = pathinfo($templateName)["extension"];
        switch($extension){
            case "php":
                $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/templates/' . $templateName;
                break;
            case "html":
                $templatePath = $_SERVER['DOCUMENT_ROOT'] . $templateName;
                break;
            default:
                $templatePath = "";
                break;
        }
        
        if (file_exists($templatePath)){

            if (isset($variables)){
                extract($variables);
            }

            ob_start(); // saves output in memory
            //callback would be the the query stuff
            include $templatePath;
            return ob_get_clean();
        }

        return 'No template file found.';
        
    }

?>
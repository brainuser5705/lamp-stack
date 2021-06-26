<?php

    /**
     * This function renders the HTML file (typically will be index.php).
     * 
     * @param templateName - php file with the html markup
     * @param variables - context variables that will be render into the template
     * 
     * @return - output containing the HTML markup, else an error message if no template file is found
     * 
     */
    function render($templateName, $variables=null, $folder="/templates/"){

        // determine where the template file is located
        $extension = pathinfo($templateName)["extension"];
        switch($extension){
            case "php":
                $templatePath = $_SERVER['DOCUMENT_ROOT'] . $folder . $templateName;
                break;
            case "html":
                $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $templateName;
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
            include $templatePath;
            return ob_get_clean(); 
        }

        return 'No template file found <code>' . $templateName . '</code>';
        
    }

?>
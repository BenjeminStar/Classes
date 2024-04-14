<?php
  class console{
    
    /**
     * Create an output script with a given function and value to be executed in the browser.
     *
     * @param string $function The function to be executed in the console.
     * @param mixed $value The value to be passed as an argument to the function.
     */
    private static function createOutput(string $function, mixed $value=null): void {
      echo  '<script class="js_auto_delete_class">';
      echo    "console.{$function}({$value});";
      echo    'var el = document.body.getElementsByClassName("js_auto_delete_class");';
      echo    'while(el[0]){el[0].parentNode.removeChild(el[0]);}';
      echo  '</script>';
    }
		
		/**
		 * Asserts the given expression and creates an output with the expression and message.
		 *
		 * @param bool $expression The expression to be evaluated.
		 * @param string $message The message associated with the expression.
		 * @return null
		 */
    public static function assert(bool $expression, string $message): null{
      if($expression === true)
        $expression = "true";
      else if($expression === false)
        $expression = "false";
      else
        return null;
      console::createOutput("assert", "{$expression}, '{$message}'");
    }
    
    /**
     * Clears the output.
     */
    public static function clear(): void{
      console::createOutput("clear");
    }
    
    public static function count(mixed $var=null): void{
      console::createOutput("count", $var);
    }

    public static function countReset(mixed $var=null): void{
      console::createOutput("countReset", $var);
    }

    public static function debug(mixed $data): void{
      if(is_array($data))
        console::createOutput("debug", json_encode($data));
      else if(is_string($data))
        console::createOutput("debug", "'$data'");
      else
        console::createOutput("debug", $data);
    }

    public static function dir(mixed $data): void{
      console::createOutput("dir", $data);
    }

    public static function dirxml(mixed $data): void{
      console::createOutput("dirxml", $data);
    }


    public static function error(mixed $data): void{
      if(is_array($data))
        console::createOutput("error", json_encode($data));
      else if(is_string($data))
        console::createOutput("error", "'$data'");
      else
        console::createOutput("error", $data);
    }

    public static function group(string $label="Group without label name"): void{
      console::createOutput("group", "'$label'");
    }

    public static function groupCollapsed(mixed $data): void{
      console::createOutput("groupCollapsed", "'$data'");
    }

    public static function groupEnd(): void{
      console::createOutput("groupEnd", null);
    }

    public static function info(mixed $data): void{
      if(is_array($data))
        console::createOutput("info", json_encode($data));
      else if(is_string($data))
        console::createOutput("info", "'$data'");
      else
        console::createOutput("info", $data);
    }

    public static function log(mixed $data): void{
      console::createOutput("log", json_encode($data));
    }

    public static function table(mixed $data): void{
      console::createOutput("table", json_encode($data));
    }

    public static function time(string $label=""): void{
      console::createOutput("time", "'$label'");
    }

    public static function timeEnd(string $label=""): void{
      console::createOutput("timeEnd", "'$label'");
    }

    public static function trace(string $label=""): void{
      console::createOutput("trace", "'$label'");
    }

    public static function warn(mixed $data): void{
      if(is_array($data))
        console::createOutput("warn", json_encode($data));
      else if(is_string($data))
        console::createOutput("warn", "'$data'");
      else
        console::createOutput("warn", $data);
    }
  }

<?php
class HtmlView extends ApiView {
    public function render($content) {
        if (!is_array($content)) {
        	if ( isset($content)) {
	        	echo "<ul>";
		        foreach ($content as $key => $value) {
		        	echo "<li>$key: $value</li>";
		        }
		        echo "</ul>";
		    }
    	}
    	else {
    		foreach ($content as $k => $v) {
    			echo "<ul>";
    			foreach ($v as $key => $value) {
    				echo "<li>$key: $value</li>";
    			}
    			echo "</ul>";
    		}
    	}
        return true;
    }
} 

?>
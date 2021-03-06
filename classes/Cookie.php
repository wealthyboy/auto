<?php
class Cookie{
	protected $options;
	
	//setup some configuration
	public function __construct(array $options){
	
		$this->options = $options;
		ob_start();
		register_shutdown_function(array(&$this, 'flush_cookies'));
		
	}
	
	//automatically adds the prefix in
	public function set_cookie($name = '', $value = '', $expire = ''){
		if(!is_numeric($expire)){
			$expire = time() - 86500;
		}else{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}
		
		return setcookie($this->options['cookie_prefix'] . $name, $value, $expire, $this->options['cookie_path'], $this->options['cookie_domain'], $this->options['cookie_secure'], $this->options['cookie_httponly']);
		
	}
	
	public function get_cookie($index = ''){
		return $this->fetch_from_array($_COOKIE, $this->options['cookie_prefix'] . $index);
	
	}
	
	public function delete_cookie($name = ''){
	
		$name = $this->options['cookie_prefix'] . $name;
		unset($_COOKIE[$name]);
		return $this->set_cookie($name, '', '');
		
	}
	
	protected function fetch_from_array(&$array, $index = ''){
	
		if(isset($array[$index])){
		
			$value = $array[$index];
			
		}elseif(($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1){
		
			$value = $array;
			for ($i = 0; $i < $count; $i++){
			
				$key = trim($matches[0][$i], '[]');
				// Empty notation will return the value as array
				if($key === ''){
					break;
				}
				if(isset($value[$key])){
					$value = $value[$key];
				}else{
					return NULL;
				}
				
			}
			
		}else{
		
			return NULL;
		
		}
		
		return $value;
		
	}
	/**
	 * Flushes the cookies that were in the output buffer.
	 */
	public function flush_cookies(){
		ob_flush();
		
	}
}
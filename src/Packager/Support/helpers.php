<?php 

if(!function_exists('str_namespace')) 
{
	function str_namespace($value) {
		return studly_case($value);
	}
}

if(!function_exists('str_repo_vendor')) 
{
	function str_repo_vendor($value) {
		return str_slug($value);
	}
}

if(!function_exists('str_repo_package')) 
{
	function str_repo_package($value) {
		return str_slug($value);
	}
}

if(!function_exists('str_class_vendor')) 
{
	function str_class_vendor($value) {
		return studly_case($value);
	}
}

if(!function_exists('str_class_package')) 
{
	function str_class_package($value) {
		return studly_case($value);
	}
}

if(!function_exists('str_var_singular')) 
{
	function str_var_singular($value) {
		return str_singular(strtolower($value));
	}
}

if(!function_exists('str_var_plural')) 
{
	function str_var_plural($value) {
		return str_plural(strtolower($value));
	}
}
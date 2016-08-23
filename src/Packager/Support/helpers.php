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

if(!function_exists('str_model')) 
{
	function str_model($value) {
		return studly_case(str_singular($value));
	}
}

if(!function_exists('str_controller')) 
{
	function str_controller($value) {
		return studly_case(str_plural($value));
	}
}

if(!function_exists('str_migration')) 
{
	function str_migration($value) {
		return snake_case(str_plural($value));
	}
}

if(!function_exists('str_seeds')) 
{
	function str_seeds($value) {
		return studly_case(str_plural($value));
	}
}
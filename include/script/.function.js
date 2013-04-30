/**
 * Returns a closure which calls the provided arguments in order.
 */
function chain()
{
	/**
	 * Remove null arguments.  This is to permit the indiscriminant use of chain
	 * with event handlers, which may have not yet been defined.
	 *
	 * @note The call to compact() depends on the Prototype library.
	 */
	var functions = Array.prototype.slice.call(arguments).compact();
	
	return function()
	{
		for (var i = 0; i < functions.length; ++i)
			functions[i].apply(this, arguments);
	}
}

$(function() {
	function selectButton(name, value)
	{
		$('input:radio[name='+ name +']').attr('checked', false);
		$('input:radio[name='+ name +'][value='+ value +']').attr('checked', true);
	}
});
$(document).ready(function(){
	$('.add-div').click(function(e){
		if($('.ac-count').length<10){
			var addDiv = $(this).parent().parent().parent().parent().clone();
			addDiv.find('.floating-input').val('');
			addDiv.find('.add-div').attr({
			  'data-toggle': "tooltip",
			  'data-placement': "bottom",
			  'title': "Click to remove",
			  'class':'btn btn-default rm-div'
			});
			addDiv.find('.fa-plus').attr('class','fa fa-minus');
			$('#action-content').append(addDiv);
		}
	});

	$('.comment-div').click(function(e){
		if($('.cm-count').length<10){
			var commentDiv = $(this).parent().parent().parent().parent().clone();
			commentDiv.find('.floating-input').val('');
			commentDiv.find('.comment-div').attr({
			  'data-toggle': "tooltip",
			  'data-placement': "bottom",
			  'title': "Click to remove",
			  'class':'btn btn-default rm-div'
			});
			commentDiv.find('.fa-plus').attr('class','fa fa-minus');
			$('#comment-content').append(commentDiv);
		}
	});

	$(document).on('click','.rm-div',function(e){
		$(this).parent().parent().parent().parent().remove();
	});

	$('.filter-icon').click(function(){
		$('.lead-form-apply').toggle(100);
	});
	
	$(document).on('click', '.m-dropdown', function (e) {
  		e.stopPropagation();
	});

	var FromStartDate = new Date();
});

$.fn.sort = function (options) {
	var obj = $(this);
	
	function sortColumn(index, asc, numberCol)
	{
		var i;
		var j;
		var rowCount = obj.find("tbody tr").length;
		var rows = obj.find("tbody tr");
		
		for(i = 0; i < rowCount; i++)
		{
			for(j = i+1; j < rowCount; j++)
			{
				var i_val = $(rows[i]).find("td:eq(" + index + ")").html();
				var j_val = $(rows[j]).find("td:eq(" + index + ")").html();

				if(numberCol) {
					i_val = parseInt(i_val); if(isNaN(i_val)) i_val = 0; 
					j_val = parseInt(j_val); if(isNaN(j_val)) j_val = 0;
				}
				
				if(( asc && (i_val > j_val) ) || ( !asc && (i_val < j_val) ))
				{
					var i_row = $(rows[i]).html();
					var j_row = $(rows[j]).html();

					$(rows[i]).html(j_row);
					$(rows[j]).html(i_row);
				} 
			}
		}
	}

	sortColumn(options.index, options.ascending, options.numberCol);
	
}
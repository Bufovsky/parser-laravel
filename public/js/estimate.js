				function estimateAnimation( )
				{
					var estimateTime = $( ".estimate b" ).html( ).split( ":" );
					
					var seconds = new Date(0, 0, 0, estimateTime[0], estimateTime[1], estimateTime[2], 0).getTime() - 1;
					
					var dateStamp = new Date(seconds).toLocaleTimeString();

					$( ".estimate b" ).html( dateStamp );

				}

				window.setInterval( function( ){
					estimateAnimation( );
				}, 1000);
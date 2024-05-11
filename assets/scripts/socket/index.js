

var socket = io.connect( 'http://'+window.location.hostname+':3000' );


var department_socket = {
    'BF' : function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('fire', { 
            fire: content,
        })
    },
    'CH' :  function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('cho', { 
            cho: content,
        })
    },
    'CV' :  function(data){
        var content = {
            data : data,
            cvo : socket.id
        };
        socket.emit('cvo', { 
            cvo: content,
        })
    },
    'MD' :  function(data){
        var content = {
            data : data,
            md : socket.id
        };
        socket.emit('md', { 
            md: content,
        })
    },
    'CN' :  function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('cenro', { 
            cenro: content,
        })
    },
    'CT' :  function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('cto', { 
            cto: content,
        })
    },
    'CP' :  function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('cpdo', { 
            cpdo: content,
        })
    },
    'CE' :  function(data){
        var content = {
            data : data,
            from : socket.id
        };
        socket.emit('ceo', { 
            ceo: content,
        })
    },

};

socket.on( 'bplo', function( data ) {
    $('#Onprocess').text(data.data);
});

function sytem_status()
{
    if(navigator.onLine)
    {
        // alert("Browser is online");
        // console.log('online');
    }
    else
    {
        // console.log('offline');
    }
}

// $(document).on('click','#btn',function(){

    
//     socket.emit('fire', { 
//         fire: 'HI fire',
//     });
    
// });

$(document).ready(function(){
    setInterval(sytem_status,1000);
});

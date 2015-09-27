require('es5-shim');
var $ = require('jQuery');
var Request = require("./utils/request");
var Env = require('./constants/Env');

Request = Object.create(Request).init({
  connectorsUrl: Env.siteConnectorsUrl,
  connector: Env.connector
});

window.ShopMODX = {
    Request: Request,
    Env: Env
};

// Форматирование даты
Date.prototype.yyyymmdd = function() {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
    var dd  = this.getDate().toString();
    return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' +(dd[1]?dd:"0"+dd[0]); // padding
};


$(function() {

  $('[data-smodx-behav="recount"]').remove();

  $("#loginLoginForm [type=submit]").on('click', function() {
    var form = $(this).parents('form:first');
    var data = [];

    form.serialize().split('&').map(function(node) {
      if (node.match('action')) return;
      this.push(node);
    }, data);

    Request.run('login', data.join('&'))
      .then(function(resp) {
        if (resp.success) {
          var url = window.location.pathname;
          window.location.replace(url);
        }
      });

    return false;
  });


});

// Добавить в Избранное
window.add_favorite = function(a) {
    title=document.title;
    url=document.location;
    try {
    // Internet Explorer
    window.external.AddFavorite(url, title);
    }
    catch (e) {
    try {
    // Mozilla
    window.sidebar.addPanel(title, url, "");
    }
    catch (e) {
    // Opera и Firefox 23+
    if (typeof(opera)=="object" || window.sidebar) {
    a.rel="sidebar";
    a.title=title;
    a.url=url;
    a.href=url;
    return true;
    }
    else {
    // Unknown
    alert('Нажмите Ctrl-D чтобы добавить страницу в закладки');
    }
    }
    }
    return false;
} 

window.site = {
    sb: {
		scroll: null,
		count: 0,
		now: 2,
		init: function() {
			site.sb.scroll = $("#sb_block .scroll");
			site.sb.scroll.scrollTop(101);
			site.sb.count = parseInt( site.sb.scroll.find("a").length );
			site.sb.now = 2;

			$("#sb_block .sb-down").click(function() {
				site.sb.scroll.append(site.sb.scroll.find("a").eq(0));
				site.sb.scroll.stop().scrollTop(0);
				site.sb.scroll.animate({scrollTop : '101px'}, 400);
				return false;
			});
			$("#sb_block .sb-up").click(function() {
				site.sb.scroll.prepend(site.sb.scroll.find("a").eq(site.sb.count - 1));
				site.sb.scroll.stop().scrollTop(202);
				site.sb.scroll.animate({scrollTop : '101px'}, 400);
				return false;
			});
		}
	}
}



$(function() {

    $('[data-comment-action]').on('click', this, function(e){
        console.log(this);
        
        var el = $(this);
        var action = el.data('comment-action');
        
        // switch(el.data('comment-action')){
        //     
        // }
        
        Request.run('comments/' + action, {
            comment_id: $(this).data('comment-id')
        })
            .then(function(resp) {
                if (resp.success) {
                    alertify.success(resp.message || "Действие успешно выполнено");
                    var url = window.location.href.replace(/\?.*/, '');
                    window.location.replace(url);
                }
            });
  }); 


});


/*
    modEconomy
*/

// Editor




var network = null;
// randomly create some nodes and edges

var seed = 2;
 

function destroy() {
  if (network !== null) {
    network.destroy();
    network = null;
  }
}

function draw(data) {
  destroy();
  nodes = [];
  edges = [];

    // create a network
    var container = document.getElementById('mynetwork');
  
    $(container).html('');
  
  var options = {
       
      
    layout: {
        // randomSeed:seed
        // ,hierarchical: {
        //     direction: "Up-Down"
        // }
    }, // just to make sure the layout is the same when the locale is changed
    // locale: 'en',
    manipulation: {
      addNode: function (data, callback) {
          
          // data.sdfsd = 'sdfsdfsdf';
            
        // filling in the popup DOM elements
        
            document.getElementById('operation').innerHTML = "Добавить элемент";
            document.getElementById('node-id').value = '';
            document.getElementById('node-label').value = '';
            document.getElementById('saveButton').onclick = addData.bind(this, data, callback);
            // console.log(this);
            // console.log(saveData.bind);
            document.getElementById('cancelButton').onclick = clearPopUp.bind();
            document.getElementById('network-popUp').style.display = 'block';
          /*
            Объект
          */
          console.log(data);
      },
      editNode: function (data, callback) {
        // filling in the popup DOM elements
        document.getElementById('operation').innerHTML = "Редактировать";
        document.getElementById('node-id').value = data.id;
        document.getElementById('node-label').value = data.name;
        document.getElementById('node-type').value = data.type;
        document.getElementById('saveButton').onclick = updateData.bind(this, data, callback);
        document.getElementById('cancelButton').onclick = cancelEdit.bind(this,callback);
        document.getElementById('network-popUp').style.display = 'block';
              /*
                Объект
              */
              console.log(data);
      }, 
      
      deleteNode: function (data, callback) { 
        
        deleteData(data, callback);
        
        
        
        return data;
      },
      
      deleteEdge: function (data, callback) { 
        
        console.log(data);
        
        // callback();
        // 
        // return;
        
        var id = data.edges[0];
          
        var post_data = {
            "id": id
        };  
         
        
        ShopMODX.Request.run('essences/processes/remove', post_data, '', 'public/components/modeconomy/connectors/connector.php')
        .then(function(r){ 
          var response = r.response 
          if(response.success != true){ 
            if(response.data.length){
              
              var i = 0;
              while(i < response.data.length){
                var d = response.data[i]; 
                alertify.warning(d.msg); 
                i++;
              }
            }
            
            if(response.message == ''){
                alertify.error("Ошибка выполнения запроса");
            }
            
            callback(true); 
            return;
          } 
            // clearPopUp();
             
            
            callback(data); 
        }) 
        
        
        
        return data;
      },
        addEdge: function (data, callback) { 
            
            if (data.from === data.to) {
                alert("Нельзя привязать объект к самому себе"); 
                callback(); 
            }
            else { 
                var panel = $('#edge-popUp');
                panel.css({
                    position: 'absolute'
                    ,top: $(window).height() / 2 - (panel.height() / 2) 
                    ,left: $(window).width() / 2 - (panel.width() / 2) 
                });
                panel.show();
                
                var from_id_field = panel.find('.from-id');
                var to_id_field = panel.find('.to-id');
                
                from_id_field.val(data.from);
                to_id_field.val(data.to);
                
                var submit = panel.find('.saveButton');
                
                submit[0].onclick = SaveNewEdge.bind(this, data, callback, panel); 
            }
            
            
            /*
                Объект
            */
            console.log(data);    
        }
    }
    
    ,edges:{
        arrows:'to'
        ,smooth: {
          // type: 'continuous'
          // type: 'discrete'
          type: 'horizontal'
          ,roundness: 0.2
        }
    }
    
    // ,
    // interaction: {
    //     // hideEdgesOnDrag: true,
    //     // tooltipDelay: 200
    // },
    //   
    ,physics: {
        enabled: false
    //     // stabilization: {
    //     //     onlyDynamicEdges: true
    //     // }
    //     // ,timestep: 0.1
    //     forceAtlas2Based: {
    //         
    //     }
    //     ,repulsion: {
    //         damping: 0.5,
    //         springConstant: 0.5
    //         ,centralGravity: 1
    //     }
    //     
    //     ,hierarchicalRepulsion: {
    //         centralGravity: 0.8
    //         ,springConstant: 0.6
    //         ,damping: 1
    //     }
    }
  };
  network = new vis.Network(container, data, options);
  
  // window.network = network;
  
    network.on("oncontext", function (params) {
        console.log(this);
        console.log(params);
        
        params.event.preventDefault();
        
        var essence_id = params.nodes[0];
        // 
        var node = this.body.nodes[essence_id];
        // 
        // console.log(node);
        // 
        
        if(!node){
            return false;
        }
        
        // 
        // window.node = node;
        // 
        // 
        var ids = String(essence_id).split('-');
        // 
        var id, label;
        
        if(ids.length == 3){
            
            network.openCluster(essence_id);
            
            return;
            
            id = ids[2];
        }
        else{
            id = essence_id;
        }
        
        var level = node.options.level || 0;
        var title = node.getTitle() || node.options.label;
        level++;
        // 
        // console.log('level: ' + level);
        // console.log('title: ' + title);
        // 
        // 
        // console.log(ids);
        // 
        if(node.edges.length < 2) return;
        // 
        network.clusterByConnection(essence_id, {
            clusterNodeProperties:{
                id: "cluster-" + level + '-' + id,
                // label: 'Кластер ' + title,
                label: title,
                level: level,
                title: title,
                shape: 'database',
                color:{
                    background: '#d6b8ea'
                }
            }
        });
        
        return false;
        
        // 
        // network.setData(data);
        // var clusterOptionsByData = {
        //     joinCondition:function(childOptions) {
        //         console.log(childOptions);
        //         return true;
        //     },
        //     clusterNodeProperties: {id:'cidCluster', borderWidth:3, shape:'database'}
        // }
        // network.cluster(clusterOptionsByData);
        // 
        // return;
        // 
        // 
        // console.log(this);
        // console.log(params);
        // console.log(essence_id);
        
    });
    
    
    network.on("doubleClick", function (params) {
        // params.event = "[original event]";
        // document.getElementById('eventSpan').innerHTML = '<h2>doubleClick event:</h2>' + JSON.stringify(params, null, 4);
        
        var essence_id = params.nodes[0];
        // 
        var node = this.body.nodes[essence_id];
        
        if(!node){
            return;
        }
        
        var id;
        
        var ids = String(essence_id).split('-');
        
        if(ids.length == 3){
             
            
            id = ids[2];
        }
        else{
            id = essence_id;
        }
        
        if(essence_id){
            
            var post_data = {
                essence_id: id
                ,days: 30
            };
            
            ShopMODX.Request.run('essences/count_profit', post_data, '', 'public/components/modeconomy/connectors/connector.php')
            .then(function(r){
              // console.log(r);
              var response = r.response
              // console.log(response);
              if(response.success != true){
                // console.log('sdfdsf');
                if(response.data.length){
                  
                  var i = 0;
                  while(i < response.data.length){
                    var d = response.data[i]; 
                    alertify.warning(d.msg); 
                    i++;
                  }
                }
                
                if(response.message == ''){
                    alertify.error("Ошибка выполнения запроса");
                }
                
                return;
              }
              
              // else 
              // data.id = response.object.id;
              //   clearPopUp(); 
              //   callback(data);
              
                draw_graphs(response.object);
                drawTotalGraph(response.object.types);
                
                console.log(response.object);
            })
            
        }
        
    });
    
    network.on("dragEnd", function (params, callback) {
        // params.event = "[original event]";
        // document.getElementById('eventSpan').innerHTML = '<h2>dragEnd event:</h2>' + JSON.stringify(params, null, 4);
        
        // console.log(this);
        // console.log(params);
        
        var essence_id = params.nodes[0];
        // 
        var node = this.body.nodes[essence_id];
        
        if(!node){
            return;
        }
        
        var id;
        
        var ids = String(essence_id).split('-');
        
        if(ids.length > 1){
             
            return;
        }
        else{
            id = essence_id;
        }
        
        
        // alert(callback);
        
        // return;
        
        // if(id){
            
        var post_data = {};
        
        for(var i in node){
            var d = node[i];
            if(/function|object/.test(typeof d)){
                continue;
            }
                // console.log(d);
            
            
            post_data[i] = d;
        }
        
        
        ShopMODX.Request.run('essences/update', post_data, '', 'public/components/modeconomy/connectors/connector.php')
        .then(function(r){
          // console.log(r);
          var response = r.response
          // console.log(response);
          if(response.success != true){
            // console.log('sdfdsf');
            if(response.data.length){
              
              var i = 0;
              while(i < response.data.length){
                var d = response.data[i]; 
                alertify.warning(d.msg); 
                i++;
              }
            }
            
            if(response.message == ''){
                alertify.error("Ошибка выполнения запроса");
            }
            
            return;
          }
          
          // else 
          // data.id = response.object.id;
          //   clearPopUp(); 
          //   callback(data);
          
            // draw_graphs(response.object);
            // drawTotalGraph(response.object.types);
            
            // console.log(response.object);
        })
            
        // }
        
    });
}

window.draw = draw;
 

function clearPopUp() {
  document.getElementById('saveButton').onclick = null;
  document.getElementById('cancelButton').onclick = null;
  document.getElementById('network-popUp').style.display = 'none';
}

function cancelEdit(callback) {
    clearPopUp();
    callback(null);
}

function addData(data,callback) {
      // data.id = document.getElementById('node-id').value;
      data.label = document.getElementById('node-label').value;
      data.name = document.getElementById('node-label').value;
      data.type = document.getElementById('node-type').value;
      // data.quantity = document.getElementById('node-quantity').value;
     
    var post_data = {
        "essence_id": 1,
        "name": data.name,
        "type": data.type,
        "x": data.x,
        "y": data.y,
        "quantity": data.quantity
    };  
     
    ShopMODX.Request.run('essences/create', post_data, '', 'public/components/modeconomy/connectors/connector.php')
    .then(function(r){
      // console.log(r);
      var response = r.response
      // console.log(response);
      if(response.success != true){
        // console.log('sdfdsf');
        if(response.data.length){
          
          var i = 0;
          while(i < response.data.length){
            var d = response.data[i]; 
            alertify.warning(d.msg); 
            i++;
          }
        }
        
        if(response.message == ''){
            alertify.error("Ошибка выполнения запроса");
        }
        
        return;
      }
      
      // else 
      data.id = response.object.id;
        clearPopUp(); 
        callback(data); 
    })
       
} 


function updateData(data,callback) {
      data.id = document.getElementById('node-id').value;
      data.label = document.getElementById('node-label').value;
      data.name = document.getElementById('node-label').value;
      data.type = document.getElementById('node-type').value; 
     
    var post_data = {
        "id": data.id,
        "name": data.name,
        "type": data.type,
        "description": data.description
    };  
     
    
    ShopMODX.Request.run('essences/update', post_data, '', 'public/components/modeconomy/connectors/connector.php')
    .then(function(r){ 
      var response = r.response 
      if(response.success != true){ 
        if(response.data.length){
          
          var i = 0;
          while(i < response.data.length){
            var d = response.data[i]; 
            alertify.warning(d.msg); 
            i++;
          }
        }
        
        if(response.message == ''){
            alertify.error("Ошибка выполнения запроса");
        }
        
        return;
      } 
        clearPopUp();
         
        
        callback(data); 
    }) 
} 



function deleteData(data,callback) {
    
    console.log(data);
    
    var id = data.nodes[0];
      
    var post_data = {
        "id": id
    };  
     
    
    ShopMODX.Request.run('essences/remove', post_data, '', 'public/components/modeconomy/connectors/connector.php')
    .then(function(r){ 
      var response = r.response 
      if(response.success != true){ 
        if(response.data.length){
          
          var i = 0;
          while(i < response.data.length){
            var d = response.data[i]; 
            alertify.warning(d.msg); 
            i++;
          }
        }
        
        if(response.message == ''){
            alertify.error("Ошибка выполнения запроса");
        }
        
        callback(true); 
        return;
      } 
        // clearPopUp();
         
        
        callback(data); 
    }) 
} 


function SaveNewEdge(data, callback, panel){
    
    var form = panel.find('form');
    
    // console.log(data)
    
    
    var post_data = form.serialize();
    // console.log(form);
    // console.log(post_data);
    
    ShopMODX.Request.run('essences/processes/create', post_data, '', 'public/components/modeconomy/connectors/connector.php')
    .then(function(r){
      // console.log(r);
      var response = r.response
      // console.log(response);
      if(response.success != true){
        // console.log('sdfdsf');
        if(response.data.length){
          
          var i = 0;
          while(i < response.data.length){
            var d = response.data[i]; 
            alertify.warning(d.msg); 
            i++;
          }
        }
        
        if(response.message == ''){
            alertify.error("Ошибка выполнения запроса");
        }
        
        return;
      }
      
      // else  
      
        data.id = response.object.id;
        panel.hide();
        form[0].reset();
        callback(data); 
    })
}
    

/*
    Total
*/


function drawTotalGraph(data){
    var today = new Date().yyyymmdd();
    
    // console.log(data);
    
    var container = document.getElementById('total-graph-visualization');
    
    $(container).html('');
    
    var groups = new vis.DataSet();
    
    var items = [];
    
    for(var i in data){
        var d = data[i];
        if(typeof d === 'function') continue;
        
        groups.add({
            id: i,
            content: d.name
        });
        
        items.push({
            x: today,
            y: d.quantity,
            "label":{
                "content": d.quantity
            },
            group: i
        });
    }
    
    // console.log(items);
    
    // return;
    
    // var items = {json_encode($items)};
    var dataset = new vis.DataSet(items);
    
    
    
    
        // groups.add({id: 0, content: "group0"})
        // groups.add({id: 1, content: "group1"})
        // groups.add({id: 2, content: "group2",options:{ yAxisOrientation:'right'}})
        
        // var items = [
        //     {x: '2014-06-11', y: 6, group:0},
        //     {x: '2014-06-11', y: 12, group:1},
        //     {x: '2014-06-11', y: 14, group:2}
        // ];
        // var dataset = new vis.DataSet(items);
    
    

    
    var options = {
        style:'bar',
        height: '400px',
        barChart: {
            width:80, align:'center', sideBySide:true
        }, // align: left, center, right
        drawPoints: true,
        orientation:'top',
        legend: true,
        start: today,
        end: today

    };
    var graph2d = new vis.Graph2d(container, items, groups, options);
    
}

window.drawTotalGraph = drawTotalGraph;

/*
    Eof Total
*/


/*
    Main graph
*/


function draw_graphs___(data){
    
    // console.log(data);
    
    var groups = new vis.DataSet();
    
    
    var container = document.getElementById('visualization');
    $(container).html('');
    
    var dataset = new vis.DataSet();
    
    
    for(var i in data.types){
        var d = data.types[i];
        if(typeof d === 'function') continue;
        
        // console.log(d);
        
        var items = [];
        
        groups.add({
            id: i,
            content: d.name
        });
        
        for(var day in data.days){
            var dd = data.days[day][i];
            if(typeof dd === 'function') continue;
            
            if(dd){
                
                // console.log(dd);
                
                items.push({
                    x: day,
                    y: dd.quantity,
                    "label":{
                        "content": dd.quantity
                    },
                    group: i
                });
            }
            
            
        } 
        
        // console.log(items);
        
        if(items.length){
            dataset.add(items);
        }
    } 
    
    
     
    
    var options = {
        height: '400px',
        drawPoints: {
            style: 'circle'
        },
        // interpolation: {
        //     parametrization: 'centripetal'
        // },
        // dataAxis: { visible: false},
        legend: true,
        start: new Date().yyyymmdd(),
        end: new Date(new Date().getTime() + (1000 * 60*60*24*30) ).yyyymmdd()
    };
    var graph2d = new vis.Graph2d(container, dataset, groups, options);
}

function draw_graphs(data){
    
    // console.log(data);
    
    var container = document.getElementById('visualization');
    $container = $(container);
    $container.html('');
    
    
    for(var i in data.types){
        
        var d = data.types[i];
        if(typeof d === 'function') continue;
        
        var container_id = "graph_" + i + "_container";
        
        var div = $('<div class="" id="'+ container_id +'" style="margin-bottom: 10px;"></div>');
        
        $container.append(div);
        
        var sub_container = div[0];
        
        
        var groups = new vis.DataSet();
        
        
        
        var dataset = new vis.DataSet();
        
        
        
        // console.log(d);
        
        var items = [];
        
        groups.add({
            id: i,
            content: d.name
        });
        
        var index = 1;
        for(var day in data.days){
            var dd = data.days[day][i];
            if(typeof dd === 'function') continue;
            
            if(dd){
                
                // console.log(dd);
                var item = {
                    x: day,
                    y: dd.quantity,
                    group: i
                };
                
                if(index%7 == 0 || index ==data.days.length - 1){
                    item.label = {
                        "content": dd.quantity
                    }
                }
                
                items.push(item);
            }
            
            index++;
        } 
        
        // console.log(items);
        
        if(items.length){
            dataset.add(items);
            
            var options = {
                height: '200px',
                drawPoints: {
                    style: 'circle'
                },
                // interpolation: {
                //     parametrization: 'centripetal'
                // },
                // dataAxis: { visible: false},
                legend: true,
                start: new Date().yyyymmdd(),
                end: new Date(new Date().getTime() + (1000 * 60*60*24*30) ).yyyymmdd()
            };
            
            var graph2d = new vis.Graph2d(sub_container, dataset, groups, options);
        }
        
        
    } 
    
    
     
    
}

window.draw_graphs = draw_graphs;

/*
    Eof Main graph
*/


/*
    EOF modEconomy
*/







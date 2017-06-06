layui.config({
	base: js_dir + '/'
}).use(['element', 'layer', 'navbar', 'tab'], function() {
	var element = layui.element(),
		$ = layui.jquery,
		layer = layui.layer,
		navbar = layui.navbar(),
		tab = layui.tab({
			elem: '.admin-nav-card' //设置选项卡容器
		});
	//iframe自适应
	$(window).on('resize', function() {
		var $content = $('.admin-nav-card .layui-tab-content');
		$content.height($(this).height() - 147);
		$content.find('iframe').each(function() {
			$(this).height($content.height());
		});
	}).resize();
    
	//设置navbar
	navbar.set({
		elem: '#admin-navbar-side',
		//data: navs,
		/*cached:true,*/
		url: navurl
	});
	//渲染navbar
	navbar.render();
	//监听点击事件
	navbar.on('click(side)', function(data) {
		tab.tabAdd(data.field);
	});

	$('.admin-side-toggle').on('click', function() {
		var sideWidth = $('#admin-side').width();
		if(sideWidth === 200) {
			$('#admin-body').animate({
				left: '0'
			}); //admin-footer
			$('#admin-footer').animate({
				left: '0'
			});
			$('#admin-side').animate({
				width: '0'
			});
		} else {
			$('#admin-body').animate({
				left: '200px'
			});
			$('#admin-footer').animate({
				left: '200px'
			});
			$('#admin-side').animate({
				width: '200px'
			});
		}
	});
	//清理缓存
	$('#cleancache').click(function(){
		$.get(cleanCache,function(data){
			if(data.success){
				layer.msg('缓存已清空！');
			}else{
				layer.msg('出错，请稍后再试');
			}
		});
	});
	//修改密码
	$('#changePWD').on('click', function() {
	   // 获取data-id属性的值 = 栏目id
		var index =	layer.open({
			type: 2,
			title: '编辑栏目',
			content: changePWD,
			area: ['600px', '400px'],
			maxmin: true
		});
	});
	//手机设备的简单适配
	var treeMobile = $('.site-tree-mobile'),
		shadeMobile = $('.site-mobile-shade');
	treeMobile.on('click', function() {
		$('body').addClass('site-mobile');
	});
	shadeMobile.on('click', function() {
		$('body').removeClass('site-mobile');
	});
});
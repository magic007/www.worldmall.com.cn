<?php echo $this->fetch('header.html'); ?> 
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.AddIncSearch-min.js'; ?>" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('#sell_time').datepicker({dateFormat: 'yy-mm-dd'});
	
	
	
	$('#buyer_remark').click(function(){var $THIS=$(this);$THIS.animate({width:'300px',height:'200px'});$THIS.keyup(function(){});$THIS.blur(function(){$THIS.animate({width:'160px',height:'18px'})})});
		
	
	
	
	
	
	
	//物流费
	$('#shipping_fee').keyup(function(){
		var t = $('#logistics_cost');
		var v = $(this).val();
		var re = /^-?\d*\.?\d{0,2}$/;
		if (!re.test(v)){
			$(this).val('');
			t.html('');
			$(this).focus();
			return false;
		}
		v = parseFloat(v);
		v = isNaN(v) ? 0 : v;
		var ar = parseFloat($('#amount_received').val());
		ar = isNaN(ar) ? 0 : ar;
		var s = parseFloat($('#sell_money').val());
		if (isNaN(s)) {s = 0;}
		var tm = v + s;
		
		t.html(v);
		$('#total_money').html(Math.round(tm * 100) / 100);
		$('#amount_uncollected').val(Math.round((tm - ar) * 100) / 100);
	});
	
	//折扣计算
	$('#discount, #sell_money').keyup(function(){
		var v = parseFloat($(this).val());
		var sa = parseFloat($('#sell_amount').text());
		var sf = parseFloat($('#shipping_fee').val());
		v = isNaN(v) ? 0 : v;
		sa = isNaN(sa) ? 0 : sa;
		sf = isNaN(sf) ? 0 : sf;
		
		var t = 0;
		if ($(this).attr('id') == 'discount'){
			t = sa * v / 100;
			$('#sell_money').val(Math.round(t * 100) / 100);
		} else {
			t = v;
			$('#discount').val(Math.round((v / sa) * 100 * 100) / 100);
		}
		
		var tm = t + sf;
		var ar = parseFloat($('#amount_received').val());
		ar = isNaN(ar) ? 0 : ar;
		
		$('#amount_uncollected').val(Math.round((tm - ar) * 100) / 100);
		$('#total_money').html(Math.round(tm * 100) / 100);
	});
	
	//已未付款计算
	$('#amount_received, #amount_uncollected').keyup(function(){
		var v = parseFloat($(this).val());
		var tm = parseFloat($('#total_money').text());
		v = isNaN(v) ? 0 : v;
		tm = isNaN(tm) ? 0 : tm;
		
		var t = Math.round((tm - v) * 100) / 100;
		if ($(this).attr('id') == 'amount_received'){
			$('#amount_uncollected').val(t);
		} else {
			$('#amount_received').val(t);
		}
	});
	
	//客户用户搜索
	$('#buyer_id').AddIncSearch({
        maxListSize   : 20,
        maxMultiMatch : 50,
		maxListWidth  : '200px',
		searchName	  : 'buyer_name',
		warnMultiMatch: '显示前{0}条匹配...',
		warnNoMatch   : '没有可匹配的选项...'
	});
	
	//回车添加一行
	$('#sell_form').keydown(function(event){
		switch(event.keyCode) {
			case 13:
				add_tr();
				break;
			default:
				return true;
		}
	});
	
	countMoney();
	addIS();
});

//计算
function countMoney(){
	$('input[name="price[]"], input[name="goods_discount[]"], input[name="quantity[]"], input[name="money[]"]').unbind().keyup(function(){
		var obj_p = $(this).parents('tr').find('td input[name="price[]"]');		    //销售单价对象
		var obj_d = $(this).parents('tr').find('td input[name="goods_discount[]"]');		//折扣率对象
		var obj_q = $(this).parents('tr').find('td input[name="quantity[]"]');		//销售数量对象
		var obj_m = $(this).parents('tr').find('td input[name="money[]"]');		    //销售金额对象
		
		//当在输入销售数量时进行检查是否合法
		if ($(this).attr('name') == 'quantity[]'){
			var re = /^[1-9]\d*$/;
			if (!re.test($(this).val())){
				$(this).val('');
			}
		}
		
		var price = isNaN(parseFloat(obj_p.val())) ? 0 : parseFloat(obj_p.val());    //销售单价
		var discount = isNaN(parseFloat(obj_d.val())) ? 0 : parseFloat(obj_d.val()); //折扣率
		var quantity = isNaN(parseInt(obj_q.val())) ? 0 : parseInt(obj_q.val());	 //销售数量
		var money = isNaN(parseFloat(obj_m.val())) ? 0 : parseFloat(obj_m.val());	 //销售金额
		
		//判断销售数量是否大于库存数量
		/*
		var mq = parseInt($(this).parents('tr').find('td input[name="max_quantity"]').val());
		var sn = $(this).parents('tr').find('td input[name="goods_sn[]"]').val();
		if (sn && (mq > 0) && (quantity > mq)){
			var msg = '销售数量不能大于库存数量%s件';
			alert(msg.replace('%s', mq));
			obj_q.val('');
			quantity = 0;
		}
		*/
		
		//当输入销售金额时计算销售单价
		if ($(this).attr('name') == 'money[]'){
			if (quantity > 0) {
				price = Math.round(money / quantity * 100) / 100;
				obj_p.val(price);
			} else {
				obj_p.val(0);
			}
		} else {
			//金额
			obj_m.val(Math.round(price * quantity * 100) / 100);
		}
		
		//折扣率
		var pr = $(this).parents('tr').find('td input[name="price_retail[]"]').val();  //零售价
		var pr = isNaN(parseFloat(pr)) ? 0 : parseFloat(pr);
		if ($(this).attr('name') == 'goods_discount[]'){
			price = Math.round(pr * discount / 100 * 100) / 100;
			obj_p.val(price);
		} else {
			if (pr > 0) {
				$(this).parents('tr').find('td input[name="goods_discount[]"]').val(Math.round(price / pr * 100 * 100) / 100);
			} else {
				$(this).parents('tr').find('td input[name="goods_discount[]"]').val(100);
			}
		}
		
		//成本金额
		var price_cost = parseFloat($(this).parents('tr').find('td input[name="price_cost[]"]').val());
		price_cost = isNaN(price_cost) ? 0 : price_cost;
		$(this).parents('tr').find('td input[name="price_total_cost[]"]').val(Math.round(price_cost * quantity * 100) / 100);
		
		//计算销售总额
		var s_c = 0;
		$('input[name="money[]"]').each(function(i, n){
			var v = parseFloat($(this).val());
			if (isNaN(v)){
				v = 0;
			}
			s_c += v;
		});
		s_c = Math.round(s_c * 100) / 100;
		
		//物流费用
		var sf = parseFloat($('#shipping_fee').val());
		if (isNaN(sf)){
			sf = 0;
		}
		var tm = s_c + sf;
		var ar = parseFloat($('#amount_received').val());
		ar = isNaN(ar) ? 0 : ar;
		
		$('#amount_uncollected').val(Math.round((tm - ar) * 100) / 100);
		$('#sell_amount').html(s_c);
		$('#discount').val(100);
		$('#sell_money').val(s_c);
		$('#total_money').html(Math.round(tm * 100) / 100);
	});
}

//商品名称搜索
function addIS(){
	$('.select_search').AddIncSearch({
        maxListSize   : 20,
        maxMultiMatch : 50,
		maxListWidth  : '600px',
		searchName	  : 'goods_name[]',
		warnMultiMatch: '显示前{0}条匹配...',
		warnNoMatch   : '没有可匹配的选项...',
		onSelect: function(list_item) {
			var obj = $(list_item);
			var v = parseInt($.trim(obj.val()));
			if (v > 0){
				$.getJSON("index.php?app=sell&act=get_goods", {id: v}, function(data){
					if (data.done){
						//没有库存不能添加
						/*
						if (parseInt(data.retval.quantity) <= 0){
							alert('该商品已没有库存，不能销售');
							obj.parents('tr').find('td input[name="goods_sn[]"]').val('');
							obj.parents('tr').find('td input[name="brand_name[]"]').val('');
							obj.parents('tr').find('td input[name="store_goods_code[]"]').val('');
							obj.parents('tr').find('td input[name="goods_colour[]"]').val('');
							obj.parents('tr').find('td input[name="unit[]"]').val('');
							obj.parents('tr').find('td input[name="goods_specification[]"]').val('');
							obj.parents('tr').find('td input[name="price_crane[]"]').val('');
							obj.parents('tr').find('td input[name="price_retail[]"]').val('');
							obj.parents('tr').find('td input[name="price[]"]').val('');
							obj.parents('tr').find('td input[name="goods_discount[]"]').val('');
							obj.parents('tr').find('td input[name="quantity[]"]').val('');
							obj.parents('tr').find('td input[name="money[]"]').val('');
							obj.parents('tr').find('td input[name="price_cost[]"]').val('');
							obj.parents('tr').find('td input[name="price_total_cost[]"]').val('');
							obj.parents('tr').find('td input[name="max_quantity"]').val(0);
							return false;
						}
						*/
						
						obj.parents('tr').find('td input[name="goods_sn[]"]').val(data.retval.goods_sn);
						obj.parents('tr').find('td input[name="brand_name[]"]').val(data.retval.brand_name);
						obj.parents('tr').find('td input[name="store_goods_code[]"]').val(data.retval.store_goods_code);
						obj.parents('tr').find('td input[name="goods_colour[]"]').val(data.retval.goods_colour);
						obj.parents('tr').find('td input[name="unit[]"]').val(data.retval.unit);
						obj.parents('tr').find('td input[name="goods_specification[]"]').val(data.retval.goods_specification);
						obj.parents('tr').find('td input[name="price_crane[]"]').val(data.retval.price_crane);
						obj.parents('tr').find('td input[name="price_retail[]"]').val(data.retval.price_retail);
						obj.parents('tr').find('td input[name="price_cost[]"]').val(data.retval.price_average);
						obj.parents('tr').find('td input[name="max_quantity"]').val(data.retval.quantity);
						
						obj.parents('tr').find('td input[name="price[]"]').focus();
						
						
						var quantity = parseInt(obj.parents('tr').find('td input[name="quantity[]"]').val());
						var price = parseFloat(obj.parents('tr').find('td input[name="price[]"]').val());
						quantity = isNaN(quantity) ? 0 : quantity;
						price = isNaN(price) ? 0 : price;
						
						//折扣率
						if (data.retval.price_retail > 0) {
							obj.parents('tr').find('td input[name="goods_discount[]"]').val(Math.round(price / data.retval.price_retail * 100 * 100) / 100);
						} else {
							obj.parents('tr').find('td input[name="goods_discount[]"]').val(100);
						}
						
						//销售金额
						obj.parents('tr').find('td input[name="money[]"]').val(Math.round(price * quantity * 100) / 100);
						
						//成本金额
						obj.parents('tr').find('td input[name="price_total_cost[]"]').val(Math.round(data.retval.price_average * quantity * 100) / 100);
					}
				});
			} else {
				obj.parents('tr').find('td input[name="goods_sn[]"]').val('');
				obj.parents('tr').find('td input[name="brand_name[]"]').val('');
				obj.parents('tr').find('td input[name="store_goods_code[]"]').val('');
				obj.parents('tr').find('td input[name="goods_colour[]"]').val('');
				obj.parents('tr').find('td input[name="unit[]"]').val('');
				obj.parents('tr').find('td input[name="goods_specification[]"]').val('');
				obj.parents('tr').find('td input[name="price_crane[]"]').val('');
				obj.parents('tr').find('td input[name="price_retail[]"]').val('');
				obj.parents('tr').find('td input[name="price[]"]').val('');
				obj.parents('tr').find('td input[name="goods_discount[]"]').val('');
				obj.parents('tr').find('td input[name="quantity[]"]').val('');
				obj.parents('tr').find('td input[name="money[]"]').val('');
				obj.parents('tr').find('td input[name="price_cost[]"]').val('');
				obj.parents('tr').find('td input[name="price_total_cost[]"]').val('');
				obj.parents('tr').find('td input[name="max_quantity"]').val(0);
			}
		}
	});
}

//增加一行
function add_tr(){
	var html = '<tr class="tatr2" style="background-color:#E1FFF0;">'+
               '<td class="firstCell"><input class="infoTableInput5" type="text" name="goods_sn[]" value="" style="width:85px;" readonly="readonly" /></td>'+
               '<td><select class="infoTableInput2 select_search" name="stock_id[]"><?php echo $this->html_options(array('options'=>$this->_var['stocks'])); ?></select></td>'+
               '<td><input class="infoTableInput5" type="text" name="brand_name[]" value="" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="store_goods_code[]" value="" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="goods_colour[]" value="" style="width:45px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="unit[]" value="" style="width:30px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="goods_specification[]" value="" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="price_crane[]" value="" style="width:50px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="price_retail[]" value="" style="width:50px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="price[]" value="" style="width:50px;" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="goods_discount[]" value="" style="width:45px;" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="quantity[]" value="" style="width:45px;" /><input name="max_quantity" type="hidden" value="0" /><input name="max_quantity" style="width:20px;" type="text" value="0" readonly="readonly" /></td>'+
			   
			   
               '<td><input class="infoTableInput5" type="text" name="money[]" value="" style="width:60px;" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="price_cost[]" value="" style="width:50px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="price_total_cost[]" value="" style="width:60px;" readonly="readonly" /></td>'+
               '<td><input class="infoTableInput5" type="text" name="remark[]" value="" style="width:50px;" /></td>'+
			   '</tr>';
	$('#input_tr tbody').append(html);
	countMoney();
	addIS();
}

//删除一行
function delete_tr(){
	if ($('#input_tr tbody tr').length <= 1){
		return;
	}
	$('#input_tr tbody tr:last').remove();
	countMoney();
	addIS();
}


$(function(){

//表单验证
$('#sell_form').validate({
	errorPlacement: function(error, element) {
		$(element).next('.field_notice').hide();
		$(element).after(error)
	},
	success: function(label) {
		label.addClass('right').text('OK!');		
	},
	onkeyup: false,
	rules: {
		client_name: {
			required: true
		},
		client_phone: {
			required: true,			
		},
		buyer: {
			required: true
		},
		order_sn: {
			required: true
		},
		'price[]':{
			required: true
		},
		'quantity[]':{
			required: true
		},
		
	},
	messages: {
		client_name: {
			required: lang.client_name_not_isnull
		},
		client_phone: {
			required: lang.client_phone_not_isnull,			
		},
		buyer: {
			required: lang.client_buyer_not_isnull
		},
		order_sn: {
			required: lang.client_order_sn_not_isnull
		},
		
	}
});


}
);



//提交
function confirm_submit() {	
	if(!confirm('是否确认无误？')){
		return false;
    }
	$('#sell_form').submit();
}
</script>
<div id="rightTop">
  <p>销售提单</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=sell">管理</a></li>
    <li><span>销售提单</span></li>
  </ul>
</div>
<form method="post" enctype="multipart/form-data" name="sell_form" id="sell_form">
  <input type="hidden" name="app" value="<?php echo $_GET['app']; ?>" />
  <input type="hidden" name="act" value="<?php echo $_GET['act']; ?>" />
  <div class="tdare">
    <table width="100%" cellspacing="0" class="dataTable">
      <thead>
        <tr class="tatr1" style="background-color:#B9FFFF;">
          <td class="firstCell">销售单号</td>
          <td>销售日期</td>
          <td>销售渠道</td>
          <td>订单编号</td>
          <td>客户用户名</td>
          <td>支付方式</td>
          <td>客户备注</td>
          <td>销售员</td>
        </tr>
      </thead>
      <tbody>
        <tr class="tatr2" style="background-color:#E1FFF0;">
          <td class="firstCell"><input name="sell_sn" type="text" class="infoTableInput4" id="sell_sn" value="<?php echo htmlspecialchars($this->_var['sell']['sell_sn']); ?>" readonly="readonly" /></td>
          <td><input class="infoTableInput3 pick_date" id="sell_time" type="text" name="sell_time" value="<?php echo htmlspecialchars($this->_var['sell']['sell_time']); ?>" readonly="readonly" /></td>
          <td><select class="querySelect" name="sell_type" id="sell_type" style="width:62px;">
              
                	<?php echo $this->html_options(array('options'=>$this->_var['sell_type'],'selected'=>$this->_var['order_info']['order_type'])); ?>
                
            </select></td>
          <td><input class="infoTableInput3" id="order_sn" type="text" name="order_sn" value="<?php echo $this->_var['order_info']['order_sn']; ?>" /></td>
          <td><select class="infoTableInput4 querySelect" name="buyer_id" id="buyer_id">
              
                	<?php echo $this->html_options(array('options'=>$this->_var['buyers'],'selected'=>$this->_var['order_info']['buyer_id'])); ?>
                
            </select></td>
          <td><input class="infoTableInput3" id="payment" type="text" name="payment" value="" /></td>
          <td style="position:relative;top:10px;width:280px;"><textarea name="buyer_remark" class="infoTableInput3" id="buyer_remark" style="position:absolute;left:0px;top:0px;"><?php echo $this->_var['order_info']['remark']; ?></textarea></td>
          <td><input class="infoTableInput3" id="buyer" type="text" name="buyer" value="" /></td>
        </tr>
        <tr class="tatr2" style="background-color:#E1FFF0;">
          <td class="firstCell">收件人姓名：</td>
          <td><span class="firstCell">
            <input name="client_name" type="text" class="infoTableInput4" id="client_name" value=""  />
            </span></td>
          <td>&nbsp;</td>
          <td colspan="2">收件人电话：<span class="firstCell">
            <input name="client_phone" type="text" class="infoTableInput4" id="client_phone" value=""  />
            </span></td>
          <td colspan="3">收件人地址<span class="firstCell">
            <input name="client_address" type="text" class="infoTableInput4" id="client_address" value="" style="width:300px;"  />
            </span></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" cellspacing="0" class="dataTable" id="input_tr">
      <thead>
        <tr class="tatr1" style="background-color: #BDF;">
          <td class="firstCell">世界窗货号</td>
          <td>商品名称</td>
          <td>品牌</td>
          <td>供应商货号</td>
          <td>颜色</td>
          <td>单位</td>
          <td>规格</td>
          <td>吊牌价</td>
          <td>零售价</td>
          <td>销售单价</td>
          <td>折扣率(%)</td>
          <td>提单数量/库存数量</td>
          <td>销售金额</td>
          <td>成本价</td>
          <td>成本金额</td>
          <td>备注</td>
        </tr>
      </thead>
      <tbody>
        <tr class="tatr2" style="background-color:#E1FFF0;">
          <td class="firstCell"><input class="hrefs infoTableInput5" type="text" name="goods_sn[]" value="" style="width:85px;" readonly="readonly" /></td>
          <td><select class="infoTableInput2 select_search" name="stock_id[]">
              
                	<?php echo $this->html_options(array('options'=>$this->_var['stocks'])); ?>
                
            </select></td>
          <td><input class="infoTableInput5" type="text" name="brand_name[]" value="" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="store_goods_code[]" value="" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="goods_colour[]" value="" style="width:45px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="unit[]" value="" style="width:30px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="goods_specification[]" value="" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="price_crane[]" value="" style="width:50px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="price_retail[]" value="" style="width:50px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="price[]" value="" style="width:50px;" /></td>
          <td><input class="infoTableInput5" type="text" name="goods_discount[]" value="" style="width:45px;" /></td>
          <td><input class="infoTableInput5" type="text" name="quantity[]" value="" style="width:45px;" />
            <input name="max_quantity" style="width:20px;" type="text" value="0" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="money[]" value="" style="width:60px;" /></td>
          <td><input class="infoTableInput5" type="text" name="price_cost[]" value="" style="width:50px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="price_total_cost[]" value="" style="width:60px;" readonly="readonly" /></td>
          <td><input class="infoTableInput5" type="text" name="remark[]" value="" style="width:50px;" /></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" cellspacing="0" class="dataTable">
      <thead>
        <tr class="tatr1" style="background-color: #B9FFFF;">
          <td width="12%" class="firstCell">销售总计</td>
          <td width="10%">折扣率(%)</td>
          <td width="10%">折后金额</td>
          <td width="10%">物流费用</td>
          <td width="10%">应收总额</td>
          <td width="10%">已收金额</td>
          <td>未收金额</td>
        </tr>
      </thead>
      <tbody>
        <tr class="tatr2" style="background-color:#E1FFF0;">
          <td class="firstCell"><span class="total_text" id="sell_amount">0.00</span></td>
          <td><input class="infoTableInput4 noInput" type="text" name="discount" id="discount" value="0.00" /></td>
          <td><input class="infoTableInput4 noInput" type="text" name="sell_money" id="sell_money" value="0.00" /></td>
          <td><span class="total_text" id="logistics_cost">0.00</span></td>
          <td><span class="total_text" id="total_money" style="color:#F00;">0.00</span></td>
          <td><input class="infoTableInput4 noInput" type="text" name="amount_received" id="amount_received" value="0.00" /></td>
          <td><input class="infoTableInput4 noInput" type="text" name="amount_uncollected" id="amount_uncollected" value="0.00" /></td>
        </tr>
      </tbody>
    </table>
    <div id="dataFuncs">
      <div class="printLinks"> <?php if (! $this->_var['data_goods']): ?> <a class="btn3" href="javascript:add_tr();">新增一行</a> <a class="btn3" href="javascript:delete_tr();">删除一行</a> <?php endif; ?> </div>
      <div class="submit-center">
        <input class="formbtn" type="button" name="Submit" value="提交" onclick="confirm_submit();" />
        <input class="formbtn" type="reset" name="Reset" value="重置" />
      </div>
    </div>
    <div class="clear"></div>
  </div>
</form>
<?php echo $this->fetch('footer.html'); ?>
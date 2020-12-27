<?php
if ( !function_exists( 'grossnet_enqueue_script' ) ) {
	function grossnet_enqueue_script(){
		if(is_page( array( 'gross-net-2020') ) ){
			wp_dequeue_style( 'twentytwenty-style');
			wp_deregister_style('twentytwenty-style');
			wp_dequeue_style( 'twentytwenty-style-inline');
			wp_deregister_style('twentytwenty-style-inline');
			wp_dequeue_style( 'twentytwenty-print-style');
			wp_deregister_style('twentytwenty-print-style');
		}
		wp_dequeue_script( 'jquery' );
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __DIR__ ) . 'public/css/bootstrap.min.css', array(), null);
		wp_enqueue_style( 'sweetalert2_css', plugin_dir_url( __DIR__ ) . 'public/css/sweetalert2.min.css', array(), null);
		wp_enqueue_style( 'custom', plugin_dir_url( __DIR__ ) . 'public/css/custom.css', array(), null);

		wp_enqueue_script( 'main-jquery', 'https://code.jquery.com/jquery-1.12.4.min.js', array(), null);
		wp_enqueue_script( 'bootstrap_js', plugin_dir_url( __DIR__ ) . 'public/js/bootstrap.min.js', array('main-jquery'), null, true);
		wp_enqueue_script( 'sweetalert2_js', plugin_dir_url( __DIR__ ) . 'public/js/sweetalert2.all.min.js', array('main-jquery'), null, true);
		wp_enqueue_script( 'money_js', plugin_dir_url( __DIR__ ) . 'public/js/simple.money.format.js', array('main-jquery'), null, true);
		wp_register_script( 'main_js', plugin_dir_url( __DIR__ ) . 'public/js/main-scripts.js', array('main-jquery'), null, true);
		wp_localize_script( 
			'main_js', 
			'ajax_obj',
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			) 
		);
		wp_enqueue_script('main_js');
	}
	add_action( 'wp_enqueue_scripts', 'grossnet_enqueue_script', 20);
}

if ( !function_exists( 'grossnet_form' ) ) {
	function grossnet_form(){
		ob_start();
		?>
		<div class="container" id="salary-content">
			<div class="main-content">
				<h1>Salary Tool Gross - Net &amp; Net - Gross</h1>
				<p class="small">Áp dụng mức giảm trừ gia cảnh mới nhất 11 triệu đồng/tháng (132 triệu đồng/năm) với nguời nộp thuế và 4,4 triệu đồng/tháng với mỗi người phụ thuộc (Theo Nghị quyết số 954/2020/UBTVQH14)</p>
				<p class="small">
					Áp dụng mức lương <a href="#" data-toggle="modal" data-target="#modal-salary-detail">tối thiểu vùng</a> mới nhất có hiệu lực từ ngày 1/1/2020 (Theo điều 3, Nghị định 90/2019/NĐ-CP)
				</p>
				<form action="" id="salary-form" method="post">
					<div class="form-group" id="salary-calculate-options">
						<div class="top-1">
							<span><strong>Áp dụng quy định:</strong></span>
							<span>
								<label for="salary-option-1" data-1="1,490,000đ" data-2="11,000,000đ" data-3="4,400,000đ">
									<input id="salary-option-1" type="radio" name="salary_option" value="1" checked="checked"/>
									<span>Từ 1/7/2020</span>
								</label>
								<span class="new">(Mới nhất)</span>
								<a href="#" class="help" data-toggle="modal" data-target="#new_option_detail_modal"><i class="glyphicon glyphicon-question-sign"></i></a>
							</span>
							<span>
								<label for="salary-option-2" data-1="1,490,000đ" data-2="9,000,000đ" data-3="3,600,000đ">
									<input id="salary-option-2" type="radio" name="salary_option" value="2"/>
									<span>Từ 1/1/2020 - 30/6/2020</span>
									<a href="#" class="help" data-toggle="modal" data-target="#old_option_detail_modal"><i class="glyphicon glyphicon-question-sign"></i></a>
								</label>
							</span>
						</div>
						<div class="top-2">
							<span>
								Lương cơ sở: <span class="text-orange data-1">1,490,000đ</span>
							</span>
							<span>
								Giảm trừ gia cảnh bản thân: <span class="text-orange data-2">11,000,000đ</span>
							</span>
							<span>
								Lương cơ sở: <span class="text-orange data-3">4,400,000đ</span>
							</span>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label for="income">Thu nhập</label>
							</div>
							<div class="col-sm-9">
							    <div class="input-group">
							      <input type="text" class="form-control" id="income" placeholder="VD: 10,000,000" name="income" />
							      <div class="input-group-addon">VNĐ</div>
							    </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label>Đóng bảo hiểm</label>
							</div>
							<div class="col-sm-4">
								<label for="dong_bao_hiem_1">
									<input id="dong_bao_hiem_1" type="radio" name="dong_bao_hiem" value="1" checked="checked"/>
									<span>Trên lương chính thức</span>
								</label>
							</div>
							<div class="col-sm-5">
								<label for="dong_bao_hiem_2">
									<input id="dong_bao_hiem_2" type="radio" name="dong_bao_hiem" value="2"/>
									<span>Khác</span>
								</label>
								<div class="input-group">
							      <input type="text" class="form-control" id="dong_bh_khac" name="dong_bh_khac" disabled/>
							      <div class="input-group-addon">VNĐ</div>
							    </div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label for="vung">Vùng</label>
								<a href="#" data-toggle="modal" data-target="#modal-salary-detail">(Giải thích)</a>
							</div>
							<div class="col-sm-9">
							    <label class="radio-inline">
								  	<input type="radio" name="region" id="inlineRadio1" value="1" checked/> I
								</label>
								<label class="radio-inline">
								  	<input type="radio" name="region" id="inlineRadio2" value="2"/> II
								</label>
								<label class="radio-inline">
								  	<input type="radio" name="region" id="inlineRadio3" value="3"/> III
								</label>
									<label class="radio-inline">
								  	<input type="radio" name="region" id="inlineRadio4" value="4"/> IV
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label for="so_nguoi_phu_thuoc">Số người phụ thuộc</label>
							</div>
							<div class="col-sm-9">
							    <input type="number" class="form-control" name="so_nguoi_phu_thuoc" value="0" min="0" />
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<input type="submit" name="groos_net" class="btn btn-success" value="GROSS → NET"/>
						<input type="submit" name="net_groos" class="btn btn-warning" value="NET → GROSS"/>
						<input type="hidden" name="action" value="groos_net_calculation"/>
						<input type="hidden" name="my_type" value=""/>
					</div>
					<div class="form-group">
						<table class="gross-net-table table table-1" style="margin-top: 40px;">
						    <tbody>
						        <tr class="rownote">
						            <th>Lương Gross</th>
						            <th>Bảo hiểm</th>
						            <th>Thuế TNCN</th>
						            <th>Lương Net</th>
						        </tr>
						        <tr>
						            <td>0</td>
						            <td>0</td>
						            <td>0</td>
						            <td>0</td>
						        </tr>
						    </tbody>
						</table>
					</div>
					<div class="form-group">
						<p class="text-left text-primary"><strong>Diễn giải chi tiết (VNĐ)</strong></p>
						<table class="gross-net-table table table-2">
						    <tbody>
						        <tr class="rownote">
						            <th>Lương GROSS</th>
						            <td><strong>0</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (8%)</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (1.5%)</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>0</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập trước thuế</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh bản thân</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh người phụ thuộc</th>
						            <td>0</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập chịu thuế</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Thuế thu nhập cá nhân(*)</th>
						            <td>0</td>
						        </tr>
						        <tr class="rownote">
						            <th><b>Lương NET</b><br> (Thu nhập trước thuế - Thuế thu nhập cá nhân)
						            </th>
						            <td><strong>0</strong></td>
						        </tr>
						    </tbody>
						</table>
					</div>
					<div class="form-group">
						<p class="text-left text-primary"><strong>(*) Chi tiết thuế thu nhập cá nhân (VNĐ)</strong></p>
						<table class="gross-net-table table table-3">
						    <tbody>
						        <tr class="rownote">
						            <th>Mức chịu thuế</th>
						            <th>Thuế suất</th>
						            <th>Tiền nộp</th>
						        </tr>
						        <tr>
						            <td>Đến 5 triệu VNĐ</td>
						            <td>5%</td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 5 triệu VNĐ đến 10 triệu VNĐ</td>
						            <td>10% </td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 10 triệu VNĐ đến 18 triệu VNĐ</td>
						            <td>15%</td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 18 triệu VNĐ đến 32 triệu VNĐ</td>
						            <td>20%</td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 32 triệu VNĐ đến 52 triệu VNĐ</td>
						            <td>25%</td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 52 triệu VNĐ đến 80 triệu VNĐ</td>
						            <td>30%</td>
						            <td>0</td>
						        </tr>
						        <tr>
						            <td>Trên 80 triệu VNĐ</td>
						            <td>35%</td>
						            <td>0</td>
						        </tr>
						    </tbody>
						</table>
					</div>
					<div class="form-group">
						<p class="text-left text-primary"><strong>Người sử dụng lao động trả (VNĐ)</strong></p>
						<table class="gross-net-table table table-4">
						    <tbody>
						        <tr>
						            <th>Lương GROSS</th>
						            <td><strong>0</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (17%)</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm Tai nạn lao động - Bệnh nghề nghiệp (0.5%)</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (3%)</th>
						            <td>0</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>0</td>
						        </tr>
						        <tr class="rownote">
						            <th>Tổng cộng</th>
						            <td><strong>0</strong></td>
						        </tr>
						    </tbody>
						</table>
					</div>
				</form>
				<div tabindex="-1" role="dialog" id="modal-salary-detail" class="modal fade">
				    <div role="document" class="modal-dialog modal-lg">
				        <div class="modal-content">
				            <div class="modal-header"><button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
				                <h4 class="modal-title text-primary"><strong>Mức lương tối thiểu vùng</strong></h4>
				                <p class="custom-font-italic" style="margin-bottom: 0px;">Áp dụng mức lương tối thiểu vùng mới nhất có hiệu lực từ ngày 1/1/2020 (Theo điều 3, Nghị định 90/2019/NĐ-CP)</p>
				            </div>
				            <div class="modal-body">
				                <p>
				                    - Vùng I: 4,420,000 đồng/tháng<br> - Vùng II: 3,920,000 đồng/tháng<br> - Vùng III: 3,430,000 đồng/tháng<br> - Vùng IV: 3,070,000 đồng/tháng<br></p>
				                <p class="text-primary"><strong>1. Vùng I, gồm các địa bàn:</strong></p>
				                <p>- Các quận và các huyện Gia Lâm, Đông Anh, Sóc Sơn, Thanh Trì, Thường Tín, Hoài Đức, Thạch Thất, Quốc Oai, Thanh Oai, Mê Linh, Chương Mỹ và thị xã Sơn Tây thuộc thành phố Hà Nội;<br> - Các quận và các huyện Thủy Nguyên, An Dương, An Lão,
				                    Vĩnh Bảo thuộc thành phố Hải Phòng;<br> - Các quận và các huyện Củ Chi, Hóc Môn, Bình Chánh, Nhà Bè thuộc thành phố Hồ Chí Minh;<br> - Thành phố Biên Hòa, thị xã Long Khánh và các huyện Nhơn Trạch, Long Thành, Vĩnh Cửu, Trảng Bom thuộc
				                    tỉnh Đồng Nai;<br> - Thành phố Thủ Dầu Một, các thị xã Thuận An, Dĩ An, Bến Cát, Tân Uyên và các huyện Bàu Bàng, Bắc Tân Uyên thuộc tỉnh Bình Dương;<br> - Thành phố Vũng Tàu, huyện Tân Thành thuộc tỉnh Bà Rịa - Vũng Tàu.</p>
				                <p class="text-primary"><strong>2. Vùng II, gồm các địa bàn:</strong></p>
				                <p>- Các huyện còn lại thuộc thành phố Hà Nội; <br> - Các huyện còn lại thuộc thành phố Hải Phòng; <br> - Thành phố Hải Dương thuộc tỉnh Hải Dương; <br> - Thành phố Hưng Yên và các huyện Mỹ Hào, Văn Lâm, Văn Giang, Yên Mỹ thuộc tỉnh Hưng
				                    Yên; <br> - Thành phố Vĩnh Yên, thị xã Phúc Yên và các huyện Bình Xuyên, Yên Lạc thuộc tỉnh Vĩnh Phúc; <br> - Thành phố Bắc Ninh, thị xã Từ Sơn và các huyện Quế Võ, Tiên Du, Yên Phong, Thuận Thành thuộc tỉnh Bắc Ninh; <br> - Các thành
				                    phố Hạ Long, Cẩm Phả, Uông Bí, Móng Cái thuộc tỉnh Quảng Ninh; <br> - Các thành phố Thái Nguyên, Sông Công và thị xã Phổ Yên thuộc tỉnh Thái Nguyên; <br> - Thành phố Việt Trì thuộc tỉnh Phú Thọ; <br> - Thành phố Lào Cai thuộc tỉnh
				                    Lào Cai; <br> - Thành phố Nam Định và huyện Mỹ Lộc thuộc tỉnh Nam Định; <br> - Thành phố Ninh Bình thuộc tỉnh Ninh Bình; <br> - Thành phố Huế thuộc tỉnh Thừa Thiên Huế; <br> - Các Thành phố Hội An, Tam kỳ thuộc tỉnh Quảng Nam; <br>                    - Các quận, huyện thuộc thành phố Đà Nẵng; <br> - Các thành phố Nha Trang, Cam Ranh thuộc tỉnh Khánh Hòa; <br> - Các thành phố Đà Lạt, Bảo Lộc thuộc tỉnh Lâm Đồng; <br> - Thành phố Phan Thiết thuộc tỉnh Bình Thuận; <br> - Huyện Cần
				                    Giờ thuộc thành phố Hồ Chí Minh; <br> - Thành phố Tây Ninh và các huyện Trảng Bàng, Gò Dầu thuộc tỉnh Tây Ninh; <br> - Các huyện Định Quán, Xuân Lộc, Thống Nhất thuộc tỉnh Đồng Nai; <br> - Các huyện còn lại thuộc tỉnh Bình Dương; <br>                    - Thị xã Đồng Xoài và huyện Chơn Thành thuộc tỉnh Bình Phước; <br> - Thành phố Bà Rịa thuộc tỉnh Bà Rịa - Vũng Tàu; <br> - Thành phố Tân An và các huyện Đức Hòa, Bến Lức, Thủ Thừa, Cần Đước, Cần Giuộc thuộc tỉnh Long An; <br> - Thành
				                    phố Mỹ Tho thuộc tỉnh Tiền Giang; <br> - Các quận thuộc thành phố Cần Thơ; <br> - Thành phố Rạch Giá, thị xã Hà Tiên và huyện Phú Quốc thuộc tỉnh Kiên Giang; <br> - Các thành phố Long Xuyên, Châu Đốc thuộc tỉnh An Giang; <br> - Thành
				                    phố Trà Vinh thuộc tỉnh Trà Vinh; <br> - Thành phố Cà Mau thuộc tỉnh Cà Mau.</p>
				                <p class="text-primary"><strong>3. Vùng III, gồm các địa bàn:</strong></p>
				                <p>- Các thành phố trực thuộc tỉnh còn lại (trừ các thành phố trực thuộc tỉnh nêu tại vùng I, vùng II);<br> - Thị xã Chí Linh và các huyện Cẩm Giàng, Nam Sách, Kim Thành, Kinh Môn, Gia Lộc, Bình Giang, Tứ Kỳ thuộc tỉnh Hải Dương;<br> - Các
				                    huyện Vĩnh Tường, Tam Đảo, Tam Dương, Lập Thạch, Sông Lô thuộc tỉnh Vĩnh Phúc;<br> - Thị xã Phú Thọ và các huyện Phù Ninh, Lâm Thao, Thanh Ba, Tam Nông thuộc tỉnh Phú Thọ;<br> - Các huyện Gia Bình, Lương Tài thuộc tỉnh Bắc Ninh;<br>                    - Các huyện Việt Yên, Yên Dũng, Hiệp Hòa, Tân Yên, Lạng Giang thuộc tỉnh Bắc Giang;<br> - Các thị xã Quảng Yên, Đông Triều và huyện Hoành Bồ thuộc tỉnh Quảng Ninh;<br> - Các huyện Bảo Thắng, Sa Pa thuộc tỉnh Lào Cai;<br> - Các huyện
				                    còn lại thuộc tỉnh Hưng Yên;<br> - Các huyện Phú Bình, Phú Lương, Đồng Hỷ, Đại Từ thuộc tỉnh Thái Nguyên;<br> - Các huyện còn lại thuộc tỉnh Nam Định;<br> - Các huyện Duy Tiên, Kim Bảng thuộc tỉnh Hà Nam;<br> - Các huyện Gia Viễn,
				                    Yên Khánh, Hoa Lư thuộc tỉnh Ninh Bình;<br> - Huyện Lương Sơn thuộc tỉnh Hòa Bình;<br> - Thị xã Bỉm Sơn và huyện Tĩnh Gia thuộc tỉnh Thanh Hóa;<br> - Thị xã Kỳ Anh thuộc tỉnh Hà Tĩnh;<br> - Các thị xã Hương Thủy, Hương Trà và các huyện
				                    Phú Lộc, Phong Điền, Quảng Điền, Phú Vang thuộc tỉnh Thừa Thiên Huế;<br> - Thị xã Điện Bàn và các huyện Đại Lộc, Duy Xuyên, Núi Thành, Quế Sơn, Thăng Bình thuộc tỉnh Quảng Nam;<br> - Các huyện Bình Sơn, Sơn Tịnh thuộc tỉnh Quảng Ngãi;<br>                    - Thị xã Sông Cầu và huyện Đông Hòa thuộc tỉnh Phú Yên;<br> - Các huyện Ninh Hải, Thuận Bắc thuộc tỉnh Ninh Thuận;<br> - Thị xã Ninh Hòa và các huyện Cam Lâm, Diên Khánh, Vạn Ninh thuộc tỉnh Khánh Hòa;<br> - Huyện Đăk Hà thuộc tỉnh
				                    Kon Tum;<br> - Các huyện Đức Trọng, Di Linh thuộc tỉnh Lâm Đồng;<br> - Thị xã La Gi và các huyện Hàm Thuận Bắc, Hàm Thuận Nam thuộc tỉnh Bình Thuận;<br> - Các thị xã Phước Long, Bình Long và các huyện Đồng Phú, Hớn Quản thuộc tỉnh
				                    Bình Phước;<br> - Các huyện còn lại thuộc tỉnh Tây Ninh;<br> - Các huyện còn lại thuộc tỉnh Đồng Nai;<br> - Các huyện Long Điền, Đất Đỏ, Xuyên Mộc, Châu Đức, Côn Đảo thuộc tỉnh Bà Rịa - Vũng Tàu;<br> - Thị xã Kiến Tường và các huyện
				                    Đức Huệ, Châu Thành, Tân Trụ, Thạnh Hóa thuộc tỉnh Long An;<br> - Các thị xã Gò Công, Cai Lậy và các huyện Châu Thành, Chợ Gạo thuộc tỉnh Tiền Giang;<br> - Huyện Châu Thành thuộc tỉnh Bến Tre;<br> - Thị xã Bình Minh và huyện Long Hồ
				                    thuộc tỉnh Vĩnh Long;<br> - Các huyện thuộc thành phố Cần Thơ;<br> - Các huyện Kiên Lương, Kiên Hải, Châu Thành thuộc tỉnh Kiên Giang;<br> - Thị xã Tân Châu và các huyện Châu Phú, Châu Thành, Thoại Sơn thuộc tỉnh An Giang;<br> - Thị
				                    xã Ngã Bảy và các huyện Châu Thành, Châu Thành A thuộc tỉnh Hậu Giang;<br> - Thị xã Duyên Hải thuộc tỉnh Trà Vinh;<br> - Thị xã Giá Rai thuộc tỉnh Bạc Liêu;<br> - Các thị xã Vĩnh Châu, Ngã Năm thuộc tỉnh Sóc Trăng;<br> - Các huyện
				                    Năm Căn, Cái Nước, U Minh, Trần Văn Thời thuộc tỉnh Cà Mau.</p>
				                <p class="text-primary"><strong>4. Vùng IV, gồm các địa bàn còn lại</strong></p>
				            </div>
				            <div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-sm btn-default">Đóng lại</button></div>
				        </div>
				    </div>
				</div>
				<div id="new_option_detail_modal" tabindex="-1" role="dialog" class="modal fade in" aria-hidden="false">
				    <div role="document" class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header"><button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
				                <h4 class="modal-title text-primary"><strong>Quy định tính lương mới nhất áp dụng từ 1/7/2020</strong></h4>
				            </div>
				            <div class="modal-body">
				                <p class="custom-font-italic">Cụ thể:</p>
				                <p>Lương cơ sở: <span class="text-orange">1,490,000đ</span></p>
				                <p>Giảm trừ gia cảnh bản thân: <span class="text-orange">11,000,000đ / tháng</span></p>
				                <p>Người phụ thuộc: <span class="text-orange">4,400,000đ / người / tháng</span></p>
				            </div>
				        </div>
				    </div>
				</div>
				<div id="old_option_detail_modal" tabindex="-1" role="dialog" class="modal fade">
				    <div role="document" class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header"><button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
				                <h4 class="modal-title text-primary"><strong>Quy định tính lương áp dụng từ 1/1/2020 đến 30/6/2020</strong></h4>
				            </div>
				            <div class="modal-body">
				                <p class="custom-font-italic">Cụ thể:</p>
				                <p>Lương cơ sở: <span class="text-orange">1,490,000đ</span></p>
				                <p>Giảm trừ gia cảnh bản thân: <span class="text-orange">9,000,000đ / tháng</span></p>
				                <p>Người phụ thuộc: <span class="text-orange">3,600,000đ / người / tháng</span></p>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	add_shortcode('grossnet_form', 'grossnet_form');
}
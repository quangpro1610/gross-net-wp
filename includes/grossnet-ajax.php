<?php
function minCalc($first, $second) {
    if ($first < $second) {
        return $first;
    }
    return $second;
}

function maxCalc($first, $second) {
    if ($first > $second) {
        return $first;
    }
    return $second;
}

function boundNumber($value, $minimum, $maximum) {
    if ($value >= $maximum) {
        return $maximum - $minimum;
    } else if ($value <= $minimum) {
        return 0;
    } else {
        return $value - $minimum;
    }
}
add_action( 'wp_ajax_groos_net_calculation', 'groos_net_calculation' );
add_action( 'wp_ajax_nopriv_groos_net_calculation', 'groos_net_calculation' );
function groos_net_calculation(){
	if($_POST['action'] != 'groos_net_calculation'){
		return;
	}
	$return = array();

	//Constant
    define('RATIO', 23000);//ti gia usd
    define('MIN_SALARY', 1490000);//luong co so
    define('AREA_MIN_SALARY_1', 4420000);//luong toi thieu vung 1
    define('AREA_MIN_SALARY_2', 3920000);//luong toi thieu vung 2
    define('AREA_MIN_SALARY_3', 3430000);//luong toi thieu vung 3
    define('AREA_MIN_SALARY_4', 3070000);//luong toi thieu vung 4
    define('SOCIAL', 8);//bao hiem xa hoi
    define('HEALTH', 1.5);//bao hiem y te
    define('UNEMPLOY', 1);//bao hiem that nghiep
    define('EMPLOYER_SOCIAL', 17);//bao hiem xa hoi nguoi su dung lao dong tra
    define('EMPLOYER_HEALTH', 3);//bao hiem y te nguoi su dung lao dong tra
    define('EMPLOYER_UNEMPLOY', 1);//bao hiem that nghiep nguoi su dung lao dong tra
    define('EMPLOYER_LABOR_ACCIDENT', 0.5);//bao hiem that nghiep nguoi su dung lao dong tra
    define('PERSONAL_REDUCE_OLD', 9000000);//giam trua gia canh ban than 1/1 - 30/6/2020
    define('DEPENDANT_REDUCE_OLD', 3600000);//nguoi phu thuoc 1/1-30/6/2020
    define('PERSONAL_REDUCE_NEW', 11000000);//giam trua gia canh ban than tu ngay 1/7/2020
    define('DEPENDANT_REDUCE_NEW', 4400000);//nguoi phu thuoc tu ngay 1/7/2020

    //Output
	$dependants = $_POST['so_nguoi_phu_thuoc'];
	$grossSalary = 0;
	$netSalary = 0;

	$socialInsurance = 0;
	$healthInsurance = 0;
	$unemployInsurance = 0;

	$noTaxSalary = 0;
	$dependantReduce = 0;
	$taxSalary = 0;

	$tax1 = 0;
	$tax2 = 0;
	$tax3 = 0;
	$tax4 = 0;
	$tax5 = 0;
	$tax6 = 0;
	$tax7 = 0;

	$totalTax = 0;

	$employerSocialInsurance = 0;
	$employerHealthInsurance = 0;
	$employerUnemployInsurance = 0;
	$employerTotal = 0;

    if($_POST['my_type'] == 'groos2net'){
		if($_POST['dong_bao_hiem'] == '1'){
			$insuranceSalary = intval(str_replace(',', '', $_POST['income']));
		}else{
			$insuranceSalary = intval(str_replace(',', '', $_POST['dong_bh_khac']));
		}
    	$grossSalary = intval(str_replace(',', '', $_POST['income']));
		$socialInsurance = minCalc($insuranceSalary, MIN_SALARY * 20) * SOCIAL / 100;
		$healthInsurance = minCalc($insuranceSalary, MIN_SALARY * 20) * HEALTH / 100;
    	switch ($_POST['region']) {
    		case '1':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_1 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_1 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '2':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_2 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_2 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '3':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_3 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_3 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '4':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_4 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_4 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		default:
    			break;
    	}
    	$totalInsurance = $socialInsurance + $healthInsurance + $unemployInsurance;
    	$noTaxSalary = $grossSalary - $socialInsurance - $healthInsurance - $unemployInsurance;
    	switch ($_POST['salary_option']) {
    		case '1':
    			$dependantReduce = DEPENDANT_REDUCE_NEW * $dependants;
    			$personalReduce = PERSONAL_REDUCE_NEW;
    			$taxSalary = maxCalc(0, $noTaxSalary - PERSONAL_REDUCE_NEW - $dependantReduce);
    			break;
    		case '2':
    			$dependantReduce = DEPENDANT_REDUCE_OLD * $dependants;
    			$personalReduce = PERSONAL_REDUCE_OLD;
    			$taxSalary = maxCalc(0, $noTaxSalary - PERSONAL_REDUCE_OLD - $dependantReduce);
    			break;
    		default:
    			break;
    	}

    	//Tax
        $tax1 = boundNumber($taxSalary, 0, 5000000) * 5 / 100;
        $tax2 = boundNumber($taxSalary, 5000000, 10000000) * 10 / 100;
        $tax3 = boundNumber($taxSalary, 10000000, 18000000) * 15 / 100;
        $tax4 = boundNumber($taxSalary, 18000000, 32000000) * 20 / 100;
        $tax5 = boundNumber($taxSalary, 32000000, 52000000) * 25 / 100;
        $tax6 = boundNumber($taxSalary, 52000000, 80000000) * 30 / 100;
        $tax7 = boundNumber($taxSalary, 80000000, 9999999999) * 35 / 100;

        $totalTax = $tax1 + $tax2 + $tax3 + $tax4 + $tax5 + $tax6 + $tax7;
        $netSalary = $noTaxSalary - $totalTax;

        //Employer
		$employerSocialInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_SOCIAL / 100;
		$employerHealthInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_HEALTH / 100;
		$employerLaborAccidentInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_LABOR_ACCIDENT / 100;
		$employerTotal = $grossSalary + $employerSocialInsurance + $employerHealthInsurance + $employerUnemployInsurance + $employerLaborAccidentInsurance;

		$return['table_1'] = '<tbody>
						        <tr class="rownote">
						            <th>Lương Gross</th>
						            <th>Bảo hiểm</th>
						            <th>Thuế TNCN</th>
						            <th>Lương Net</th>
						        </tr>
						        <tr>
						            <td>'.round($grossSalary).'</td>
						            <td>'.round($totalInsurance).'</td>
						            <td>'.round($totalTax).'</td>
						            <td>'.round($netSalary).'</td>
						        </tr>
						    </tbody>';
		$return['table_2'] = '<tbody>
						        <tr class="rownote">
						            <th>Lương GROSS</th>
						            <td><strong>'.round($grossSalary).'</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (8%)</th>
						            <td>'.round($socialInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (1.5%)</th>
						            <td>'.round($healthInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>'.round($unemployInsurance).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập trước thuế</th>
						            <td>'.round($noTaxSalary).'</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh bản thân</th>
						            <td>-'.round($personalReduce).'</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh người phụ thuộc</th>
						            <td>'.round($dependantReduce).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập chịu thuế</th>
						            <td>'.round($taxSalary).'</td>
						        </tr>
						        <tr>
						            <th>Thuế thu nhập cá nhân(*)</th>
						            <td>'.round($totalTax).'</td>
						        </tr>
						        <tr class="rownote">
						            <th><b>Lương NET</b><br> (Thu nhập trước thuế - Thuế thu nhập cá nhân)
						            </th>
						            <td><strong>'.round($netSalary).'</strong></td>
						        </tr>
						    </tbody>';
		$return['table_3'] = '<tbody>
						        <tr class="rownote">
						            <th>Mức chịu thuế</th>
						            <th>Thuế suất</th>
						            <th>Tiền nộp</th>
						        </tr>
						        <tr>
						            <td>Đến 5 triệu VNĐ</td>
						            <td>5%</td>
						            <td>'.round($tax1).'</td>
						        </tr>
						        <tr>
						            <td>Trên 5 triệu VNĐ đến 10 triệu VNĐ</td>
						            <td>10% </td>
						            <td>'.round($tax2).'</td>
						        </tr>
						        <tr>
						            <td>Trên 10 triệu VNĐ đến 18 triệu VNĐ</td>
						            <td>15%</td>
						            <td>'.round($tax3).'</td>
						        </tr>
						        <tr>
						            <td>Trên 18 triệu VNĐ đến 32 triệu VNĐ</td>
						            <td>20%</td>
						            <td>'.round($tax4).'</td>
						        </tr>
						        <tr>
						            <td>Trên 32 triệu VNĐ đến 52 triệu VNĐ</td>
						            <td>25%</td>
						            <td>'.round($tax5).'</td>
						        </tr>
						        <tr>
						            <td>Trên 52 triệu VNĐ đến 80 triệu VNĐ</td>
						            <td>30%</td>
						            <td>'.round($tax6).'</td>
						        </tr>
						        <tr>
						            <td>Trên 80 triệu VNĐ</td>
						            <td>35%</td>
						            <td>'.round($tax7).'</td>
						        </tr>
						    </tbody>';
		$return['table_4'] =	'<tbody>
						        <tr>
						            <th>Lương GROSS</th>
						            <td><strong>'.round($grossSalary).'</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (17%)</th>
						            <td>'.round($employerSocialInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm Tai nạn lao động - Bệnh nghề nghiệp (0.5%)</th>
						            <td>'.round($employerLaborAccidentInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (3%)</th>
						            <td>'.round($employerHealthInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>'.round($employerUnemployInsurance).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Tổng cộng</th>
						            <td><strong>'.round($employerTotal).'</strong></td>
						        </tr>
						    </tbody>';
    }else{
    	if($_POST['dong_bao_hiem'] == '1'){
			$insuranceSalary = intval(str_replace(',', '', $_POST['income']));
		}else{
			$insuranceSalary = intval(str_replace(',', '', $_POST['dong_bh_khac']));
		}

        $netSalary = intval(str_replace(',', '', $_POST['income']));
		$socialInsurance = minCalc($insuranceSalary, MIN_SALARY * 20) * SOCIAL / 100;
		$healthInsurance = minCalc($insuranceSalary, MIN_SALARY * 20) * HEALTH / 100;
    	switch ($_POST['region']) {
    		case '1':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_1 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_1 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '2':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_2 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_2 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '3':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_3 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_3 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		case '4':
    			$unemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_4 * 20) * UNEMPLOY / 100;
    			$employerUnemployInsurance = minCalc($insuranceSalary, AREA_MIN_SALARY_4 * 20) * EMPLOYER_UNEMPLOY / 100;
    			break;
    		default:
    			break;
    	}
    	$totalInsurance = $socialInsurance + $healthInsurance + $unemployInsurance;
    	$grossSalary = $netSalary + $totalInsurance;
    	$noTaxSalary = $grossSalary - $socialInsurance - $healthInsurance - $unemployInsurance;
    	switch ($_POST['salary_option']) {
    		case '1':
    			$dependantReduce = DEPENDANT_REDUCE_NEW * $dependants;
    			$personalReduce = PERSONAL_REDUCE_NEW;
    			$taxSalary = maxCalc(0, $noTaxSalary - PERSONAL_REDUCE_NEW - $dependantReduce);
    			break;
    		case '2':
    			$dependantReduce = DEPENDANT_REDUCE_OLD * $dependants;
    			$personalReduce = PERSONAL_REDUCE_OLD;
    			$taxSalary = maxCalc(0, $noTaxSalary - PERSONAL_REDUCE_OLD - $dependantReduce);
    			break;
    		default:
    			break;
    	}

    	//Tax
        $tax1 = boundNumber($taxSalary, 0, 5000000) * 5 / 100;
        $tax2 = boundNumber($taxSalary, 5000000, 10000000) * 10 / 100;
        $tax3 = boundNumber($taxSalary, 10000000, 18000000) * 15 / 100;
        $tax4 = boundNumber($taxSalary, 18000000, 32000000) * 20 / 100;
        $tax5 = boundNumber($taxSalary, 32000000, 52000000) * 25 / 100;
        $tax6 = boundNumber($taxSalary, 52000000, 80000000) * 30 / 100;
        $tax7 = boundNumber($taxSalary, 80000000, 9999999999) * 35 / 100;

        $totalTax = $tax1 + $tax2 + $tax3 + $tax4 + $tax5 + $tax6 + $tax7;

        //Employer
		$employerSocialInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_SOCIAL / 100;
		$employerHealthInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_HEALTH / 100;
		$employerLaborAccidentInsurance = minCalc($grossSalary, MIN_SALARY * 20) * EMPLOYER_LABOR_ACCIDENT / 100;
		$employerTotal = $grossSalary + $employerSocialInsurance + $employerHealthInsurance + $employerUnemployInsurance + $employerLaborAccidentInsurance;

		$return['table_1'] = '<tbody>
						        <tr class="rownote">
						            <th>Lương Gross</th>
						            <th>Bảo hiểm</th>
						            <th>Thuế TNCN</th>
						            <th>Lương Net</th>
						        </tr>
						        <tr>
						            <td>'.round($grossSalary).'</td>
						            <td>'.round($totalInsurance).'</td>
						            <td>'.round($totalTax).'</td>
						            <td>'.round($netSalary).'</td>
						        </tr>
						    </tbody>';
		$return['table_2'] = '<tbody>
						        <tr class="rownote">
						            <th>Lương GROSS</th>
						            <td><strong>'.round($grossSalary).'</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (8%)</th>
						            <td>'.round($socialInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (1.5%)</th>
						            <td>'.round($healthInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>'.round($unemployInsurance).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập trước thuế</th>
						            <td>'.round($netSalary).'</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh bản thân</th>
						            <td>-'.round($personalReduce).'</td>
						        </tr>
						        <tr>
						            <th>Giảm trừ gia cảnh người phụ thuộc</th>
						            <td>'.round($dependantReduce).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Thu nhập chịu thuế</th>
						            <td>'.round($taxSalary).'</td>
						        </tr>
						        <tr>
						            <th>Thuế thu nhập cá nhân(*)</th>
						            <td>'.round($totalTax).'</td>
						        </tr>
						        <tr class="rownote">
						            <th><b>Lương NET</b><br> (Thu nhập trước thuế - Thuế thu nhập cá nhân)
						            </th>
						            <td><strong>'.round($netSalary).'</strong></td>
						        </tr>
						    </tbody>';
		$return['table_3'] = '<tbody>
						        <tr class="rownote">
						            <th>Mức chịu thuế</th>
						            <th>Thuế suất</th>
						            <th>Tiền nộp</th>
						        </tr>
						        <tr>
						            <td>Đến 5 triệu VNĐ</td>
						            <td>5%</td>
						            <td>'.round($tax1).'</td>
						        </tr>
						        <tr>
						            <td>Trên 5 triệu VNĐ đến 10 triệu VNĐ</td>
						            <td>10% </td>
						            <td>'.round($tax2).'</td>
						        </tr>
						        <tr>
						            <td>Trên 10 triệu VNĐ đến 18 triệu VNĐ</td>
						            <td>15%</td>
						            <td>'.round($tax3).'</td>
						        </tr>
						        <tr>
						            <td>Trên 18 triệu VNĐ đến 32 triệu VNĐ</td>
						            <td>20%</td>
						            <td>'.round($tax4).'</td>
						        </tr>
						        <tr>
						            <td>Trên 32 triệu VNĐ đến 52 triệu VNĐ</td>
						            <td>25%</td>
						            <td>'.round($tax5).'</td>
						        </tr>
						        <tr>
						            <td>Trên 52 triệu VNĐ đến 80 triệu VNĐ</td>
						            <td>30%</td>
						            <td>'.round($tax6).'</td>
						        </tr>
						        <tr>
						            <td>Trên 80 triệu VNĐ</td>
						            <td>35%</td>
						            <td>'.round($tax7).'</td>
						        </tr>
						    </tbody>';
		$return['table_4'] =	'<tbody>
						        <tr>
						            <th>Lương GROSS</th>
						            <td><strong>'.round($grossSalary).'</strong></td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm xã hội (17%)</th>
						            <td>'.round($employerSocialInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm Tai nạn lao động - Bệnh nghề nghiệp (0.5%)</th>
						            <td>'.round($employerLaborAccidentInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm y tế (3%)</th>
						            <td>'.round($employerHealthInsurance).'</td>
						        </tr>
						        <tr>
						            <th>Bảo hiểm thất nghiệp (1%)</th>
						            <td>'.round($employerUnemployInsurance).'</td>
						        </tr>
						        <tr class="rownote">
						            <th>Tổng cộng</th>
						            <td><strong>'.round($employerTotal).'</strong></td>
						        </tr>
						    </tbody>';
    }
    die(json_encode($return));
}
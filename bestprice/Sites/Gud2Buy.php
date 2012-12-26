<?php
class Gud2Buy extends Parsing{
	public $_code = 'Gud2Buy';

	public function getAllowedCategory(){
		return array(Category::MOBILE,Category::CAMERA,Category::MOBILE_ACC);
	}

	public function getWebsiteUrl(){
		return 'http://gud2buy.com/';
	}
	public function getSearchURL($query,$category = false){
		if($category == Category::MOBILE){
			return "http://gud2buy.com/mobiles-and-accessories/mobiles&filter_name=$query&sort=stock_status_id+desc";
		}else if($category == Category::MOBILE_ACC){
			return "http://gud2buy.com/mobiles-and-accessories/accessories&filter_name=$query&sort=stock_status_id+desc";
		}else if($category == Category::CAMERA){
			return "http://gud2buy.com/cameras/digital-camera&filter_name=$query&sort=stock_status_id+desc";
			return "http://gud2buy.com/cameras/camcorder&filter_name=$query&sort=stock_status_id+desc";
		}
		return "http://gud2buy.com/search&sort=stock_status_id+desc&filter_name=$query&filters=";
	}
	public function getLogo(){
		return 'http://'.$_SERVER["SERVER_NAME"].'/scrapping/bestprice/img/guy2buy.png';
	}
	public function getData($html,$query,$category){

		$data = array();
		phpQuery::newDocumentHTML($html);
		if(sizeof(pq('.row')) > 0){
			foreach(pq('.row') as $div){
				foreach(pq($div)->children('.select_piece') as $div){
					$image = pq($div)->children('a:first')->children('.image')->html();
					$url = pq($div)->children('a:first')->attr('href');
					$name = pq($div)->children('a:first')->children('.name')->html();
					if(sizeof(pq($div)->children('a')->children('span.price'))){
						$disc_price = pq($div)->children('a')->children('span.price')->html();
					}else{
						$disc_price = pq($div)->children('a')->children('div.temp')->children('.price-new')->html();
					}
					$offer = '';
					$shipping = '';
					$stock = 0;
					$author = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => ''
					);
				}
			}
		}
		$data2 = array();
		foreach($data as $row){
			$html = $row['image'];
			$html .= '</img>';
			phpQuery::newDocumentHTML($html);
			$img = pq('img')->attr('src');
			$row['image'] = $img;
			$data2[] = $row;
		}
		$data2 = $this->cleanData($data2, $query);
		$data2 = $this->bestMatchData($data2, $query,$category);
		return $data2;
	}
}
<?php
namespace Repository;

interface SellerInterface {
	public function getData();
	public function getPoData();
	public function getPoItem();
	public function store($data);
}
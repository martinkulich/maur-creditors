<?php


/**
 * This class defines the structure of the 'contract_excluded_report' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class ContractExcludedReportTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ContractExcludedReportTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('contract_excluded_report');
		$this->setPhpName('ContractExcludedReport');
		$this->setClassname('ContractExcludedReport');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('CONTRACT_ID', 'ContractId', 'INTEGER' , 'contract', 'ID', true, null, null);
		$this->addForeignPrimaryKey('REPORT_CODE', 'ReportCode', 'VARCHAR' , 'report', 'CODE', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Contract', 'Contract', RelationMap::MANY_TO_ONE, array('contract_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('Report', 'Report', RelationMap::MANY_TO_ONE, array('report_code' => 'code', ), 'CASCADE', 'CASCADE');
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
		);
	} // getBehaviors()

} // ContractExcludedReportTableMap

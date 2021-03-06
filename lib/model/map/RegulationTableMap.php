<?php


/**
 * This class defines the structure of the 'regulation' table.
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
class RegulationTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.RegulationTableMap';

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
		$this->setName('regulation');
		$this->setPhpName('Regulation');
		$this->setClassname('Regulation');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'VARCHAR', true, 255, null);
		$this->addColumn('CREDITOR_FULLNAME', 'CreditorFirstname', 'VARCHAR', false, 255, null);
		$this->addForeignKey('CREDITOR_ID', 'CreditorId', 'INTEGER', 'subject', 'ID', false, null, null);
		$this->addForeignKey('CONTRACT_ID', 'ContractId', 'INTEGER', 'contract', 'ID', false, null, null);
		$this->addColumn('CONTRACT_NAME', 'ContractName', 'VARCHAR', false, 255, null);
		$this->addForeignKey('REGULATION_YEAR', 'RegulationYear', 'VARCHAR', 'regulation_year', 'ID', false, 255, null);
		$this->addColumn('START_BALANCE', 'StartBalance', 'NUMERIC', false, 15, null);
		$this->addColumn('CONTRACT_ACTIVATED_AT', 'ContractActivatedAt', 'DATE', false, null, null);
		$this->addColumn('CONTRACT_BALANCE', 'ContractBalance', 'NUMERIC', false, 15, null);
		$this->addColumn('REGULATION', 'Requlation', 'NUMERIC', false, 15, null);
		$this->addColumn('PAID', 'Paid', 'NUMERIC', false, 15, null);
		$this->addColumn('PAID_FOR_CURRENT_YEAR', 'PaidForCurrentYear', 'NUMERIC', false, 15, null);
		$this->addColumn('CAPITALIZED', 'Capitalized', 'NUMERIC', false, 15, null);
		$this->addColumn('UNPAID', 'Unpaid', 'NUMERIC', false, 15, null);
		$this->addColumn('UNPAID_IN_PAST', 'UnpaidInPast', 'NUMERIC', false, 15, null);
		$this->addColumn('END_BALANCE', 'EndBalance', 'NUMERIC', false, 15, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Contract', 'Contract', RelationMap::MANY_TO_ONE, array('contract_id' => 'id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Subject', 'Subject', RelationMap::MANY_TO_ONE, array('creditor_id' => 'id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('RegulationYearRelatedByRegulationYear', 'RegulationYear', RelationMap::MANY_TO_ONE, array('regulation_year' => 'id', ), 'RESTRICT', 'CASCADE');
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

} // RegulationTableMap

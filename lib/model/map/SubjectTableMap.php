<?php


/**
 * This class defines the structure of the 'subject' table.
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
class SubjectTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SubjectTableMap';

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
		$this->setName('subject');
		$this->setPhpName('Subject');
		$this->setClassname('Subject');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('subject_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('SUBJECT_TYPE_CODE', 'SubjectTypeCode', 'VARCHAR', true, 255, null);
		$this->addColumn('IDENTIFICATION_NUMBER', 'IdentificationNumber', 'VARCHAR', true, 255, null);
		$this->addColumn('FIRSTNAME', 'Firstname', 'VARCHAR', false, 255, null);
		$this->addColumn('LASTNAME', 'Lastname', 'VARCHAR', true, 255, null);
		$this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 255, null);
		$this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 255, null);
		$this->addColumn('BANK_ACCOUNT', 'BankAccount', 'VARCHAR', false, 255, null);
		$this->addColumn('CITY', 'City', 'VARCHAR', false, 255, null);
		$this->addColumn('STREET', 'Street', 'VARCHAR', false, 255, null);
		$this->addColumn('ZIP', 'Zip', 'VARCHAR', false, 255, null);
		$this->addColumn('NOTE', 'Note', 'LONGVARCHAR', false, null, null);
		$this->addColumn('BIRTH_DATE', 'BirthDate', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('ContractRelatedByCreditorId', 'Contract', RelationMap::ONE_TO_MANY, array('id' => 'creditor_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('ContractRelatedByDebtorId', 'Contract', RelationMap::ONE_TO_MANY, array('id' => 'debtor_id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Gift', 'Gift', RelationMap::ONE_TO_MANY, array('id' => 'creditor_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('OutgoingPayment', 'OutgoingPayment', RelationMap::ONE_TO_MANY, array('id' => 'creditor_id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Regulation', 'Regulation', RelationMap::ONE_TO_MANY, array('id' => 'creditor_id', ), 'RESTRICT', 'CASCADE');
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

} // SubjectTableMap

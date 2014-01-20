<?php


/**
 * This class defines the structure of the 'outgoing_payment' table.
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
class OutgoingPaymentTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.OutgoingPaymentTableMap';

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
		$this->setName('outgoing_payment');
		$this->setPhpName('OutgoingPayment');
		$this->setClassname('OutgoingPayment');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('outgoing_payment_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('BANK_ACCOUNT_ID', 'BankAccountId', 'INTEGER', 'bank_account', 'ID', false, null, null);
		$this->addColumn('AMOUNT', 'Amount', 'NUMERIC', true, 15, 0);
		$this->addColumn('DATE', 'Date', 'DATE', true, null, null);
		$this->addColumn('NOTE', 'Note', 'LONGVARCHAR', false, null, null);
		$this->addForeignKey('CURRENCY_CODE', 'CurrencyCode', 'CHAR', 'currency', 'CODE', true, 3, null);
		$this->addForeignKey('CREDITOR_ID', 'CreditorId', 'INTEGER', 'subject', 'ID', true, null, null);
		$this->addColumn('CASH', 'Cash', 'BOOLEAN', false, null, null);
		$this->addColumn('RECEIVER_BANK_ACCOUNT', 'ReceiverBankAccount', 'VARCHAR', false, 255, null);
		$this->addColumn('REFUNDATION', 'Refundation', 'NUMERIC', false, 15, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Subject', 'Subject', RelationMap::MANY_TO_ONE, array('creditor_id' => 'id', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('Currency', 'Currency', RelationMap::MANY_TO_ONE, array('currency_code' => 'code', ), 'RESTRICT', 'CASCADE');
    $this->addRelation('BankAccount', 'BankAccount', RelationMap::MANY_TO_ONE, array('bank_account_id' => 'id', ), 'SET NULL', 'CASCADE');
    $this->addRelation('Allocation', 'Allocation', RelationMap::ONE_TO_MANY, array('id' => 'outgoing_payment_id', ), 'CASCADE', 'CASCADE');
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

} // OutgoingPaymentTableMap

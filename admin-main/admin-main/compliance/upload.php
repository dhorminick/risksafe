<?php
    session_start();
    $file_dir = '../../';
    $inpage_dir = '../';
    
    $message = [];
    $accnt_dir = null;
    include $file_dir.'layout/db.php';
    include $inpage_dir.'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    $db = $con;
    $c_id = 'srq2zc9k37';
    
    $allowedFrequencies = array(
        "daily" => 1, 
        "weekly" => 2, 
        "monthly" => 4, 
        "quaterly" => 5,
        "annually" => 6,
        "half yearly" => 8,
    );
    
    $arr = array(
        array( 'section' => 'Food Business Registration',
'task' => 'Food Business Registration',
'legislation' => 'State/Territory Food Acts (e.g., Food Act 2006 QLD, Food Act 1984 VIC)',
'requirement' => 'Register with the local council as a food business',
'frequency' => 'annually',
'officer' => 'Compliance Officer / Business Owner',
'note' => 'Required before commencing operations'
),

array( 'section' => 'Food Safety Program',
'task' => 'Food Safety Program',
'legislation' => 'Food Standards Code (FSANZ)',
'requirement' => 'Implement and maintain a food safety plan',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'Some states mandate a documented plan'
),

array( 'section' => 'Food Handler Training',
'task' => 'Food Handler Training',
'legislation' => 'Food Standards Code (FSANZ) & State Regulations',
'requirement' => 'All food handlers must complete accredited food safety trainin',
'frequency' => 'ong',
'officer' => 'Manager / Compliance Officer',
'note' => 'Proof of training must be maintained'
),

array( 'section' => 'Food Storage & Temperature Control',
'task' => 'Food Storage & Temperature Control',
'legislation' => 'Food Standards Code (FSANZ)',
'requirement' => 'Maintain safe food storage practices',
'frequency' => 'ong',
'officer' => 'Kitchen Manager / Compliance Officer',
'note' => 'Keep temperature logs'
),

array( 'section' => 'Allergen Management',
'task' => 'Allergen Management',
'legislation' => 'Food Standards Code (FSANZ)',
'requirement' => 'Display allergen information & train staff',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'Non-compliance can result in legal action'
	),
    
array( 'section' => 'Minimum Wage Compliance',
'task' => 'Minimum Wage Compliance',
'legislation' => 'Fair Work Act 2009',
'requirement' => 'Pay employees per Hospitality Industry (General) Award',
'frequency' => 'ong',
'officer' => 'Payroll Officer',
'note' => 'Rates reviewed annually'
),

array( 'section' => 'Employment Contracts',
'task' => 'Employment Contracts',
'legislation' => 'Fair Work Act 2009',
'requirement' => 'Provide written contracts to employees',
'frequency' => 'ong',
'officer' => 'HR Manager',
'note' => 'Maintain copies for record-keeping'
),

array( 'section' => 'Superannuation Contributions',
'task' => 'Superannuation Contributions',
'legislation' => 'Superannuation Guarantee (Administration) Act 1992',
'requirement' => 'Pay superannuation (currently 11%)',
'frequency' => 'quarterly',
'officer' => 'Payroll Officer',
'note' => 'Late payments incur penalties'
),

array( 'section' => 'WHS Compliance',
'task' => 'WHS Compliance',
'legislation' => 'Work Health and Safety Act 2011.',
'requirement' => 'Maintain a safe working environment',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'WHS audit every 6 months'
),

array( 'section' => 'Anti-Discrimination & Harassment',
'task' => 'Anti-Discrimination & Harassment',
'legislation' => 'Fair Work Act & Anti-Discrimination Laws',
'requirement' => 'Ensure no discrimination in hiring & workplace conduct',
'frequency' => 'ong',
'officer' => 'HR Manager',
'note' => 'Training recommended annually'
		),
        			
array( 'section' => 'Risk Management Plan',
'task' => 'Risk Management Plan',
'legislation' => 'WHS Act 2011',
'requirement' => 'Identify & manage workplace risks',
'frequency' => 'annually',
'officer' => 'Compliance Officer',
'note' => 'Documented risk assessments required'
),

array( 'section' => 'Fire Safety & Emergency Plans',
'task' => 'Fire Safety & Emergency Plans',
'legislation' => 'State Fire Safety Laws',
'requirement' => 'Install extinguishers, maintain exits, train staff',
'frequency' => 'annually',
'officer' => 'Business Owner',
'note' => 'Emergency drills every 6 months'
),

array( 'section' => 'First Aid Kit & Training',
'task' => 'First Aid Kit & Training',
'legislation' => 'WHS Regulations',
'requirement' => 'Maintain first aid kits & train staff',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'At least one trained first aider on site'
),

array( 'section' => 'PPE for Kitchen Staff',
'task' => 'PPE for Kitchen Staff',
'legislation' => 'WHS Act 2011',
'requirement' => 'Ensure staff wear gloves, aprons, closed shoes',
'frequency' => 'ong',
'officer' => 'Kitchen Manager',
'note' => 'Spot checks recommended'
),

array( 'section' => 'ABN Registration',
'task' => 'ABN Registration',
'legislation' => 'ATO Regulations',
'requirement' => 'Register for an ABN before trading',
'frequency' => 'ong',
'officer' => 'Business Owner',
'note' => 'Required for all businesses'
),

array( 'section' => 'GST Compliance',
'task' => 'GST Compliance',
'legislation' => 'A New Tax System (Goods and Services Tax) Act 1999',
'requirement' => 'Register for GST if turnover > $75,000',
'frequency' => 'ong',
'officer' => 'Accountant',
'note' => 'Quarterly BAS lodgments'
),

array( 'section' => 'PAYG Withholding',
'task' => 'PAYG Withholding',
'legislation' => 'Taxation Administration Act 1953',
'requirement' => 'Withhold tax from employee wages',
'frequency' => 'ong',
'officer' => 'Payroll Officer',
'note' => 'Non-compliance incurs fines'
),

array( 'section' => 'Business Activity Statements (BAS)',
'task' => 'Business Activity Statements (BAS)',
'legislation' => 'Tax Laws',
'requirement' => 'Lodge BAS with the ATO',
'frequency' => 'quarterly',
'officer' => 'Accountant',
'note' => 'Timely submission avoids penalties'
),

array( 'section' => 'Payroll Tax',
'task' => 'Payroll Tax',
'legislation' => 'State-based Payroll Tax Laws',
'requirement' => 'Pay payroll tax if threshold exceeded',
'frequency' => 'monthly',
'officer' => 'Accountant',
'note' => 'Varies by state'
),

array( 'section' => 'Liquor License',
'task' => 'Liquor License',
'legislation' => 'State Liquor Acts',
'requirement' => 'Apply for & renew liquor license',
'frequency' => 'annually',
'officer' => 'Business Owner',
'note' => 'Varies by state'
),

array( 'section' => 'RSA Training',
'task' => 'RSA Training',
'legislation' => 'Liquor Licensing Laws',
'requirement' => 'Ensure all staff complete Responsible Service of Alcohol (RSA) training	Upon employment & refresher',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'Proof of training must be retained'
),

array( 'section' => 'Compliance with Trading Hours',
'task' => 'Compliance with Trading Hours',
'legislation' => 'Liquor Licensing Laws',
'requirement' => 'Follow licensed trading hours',
'frequency' => 'ong',
'officer' => 'Manager',
'note' => 'Breaches may lead to fines'
		),
        			
array( 'section' => 'Data Security Measures',
'task' => 'Data Security Measures',
'legislation' => 'Privacy Act 1988 & Australian Privacy Principles (APPs)',
'requirement' => 'Secure customer & employee data',
'frequency' => 'ong',
'officer' => 'IT Manager',
'note' => 'Implement cybersecurity measures'
),

array( 'section' => 'Privacy Policy',
'task' => 'Privacy Policy',
'legislation' => 'Privacy Act 1988',
'requirement' => 'Have a clear privacy policy for customers',
'frequency' => 'annually',
'officer' => 'Compliance Officer',
'note' => 'Review & update regularly'
),

array( 'section' => 'Fair Pricing & Honest Advertising',
'task' => 'Fair Pricing & Honest Advertising',
'legislation' => 'Australian Consumer Law (ACL)	',
'requirement' => 'Ensure accurate menu pricing & no false advertising',
'frequency' => 'ong',
'officer' => 'Manager',
'note' => 'Misleading conduct penalties apply'
),

array( 'section' => 'Refund & Returns Policy',
'task' => 'Refund & Returns Policy',
'legislation' => 'Australian Consumer Law (ACL)	',
'requirement' => 'Have a clear refund policy if selling retail goods',
'frequency' => 'ong',
'officer' => 'Business Owner',
'note' => 'Must comply with ACL standards'
),

array( 'section' => 'Waste Disposal & Recycling',
'task' => 'Waste Disposal & Recycling',
'legislation' => 'Local Government Regulations',
'requirement' => 'Proper disposal of waste & recycling compliance',
'frequency' => 'ong',
'officer' => 'Kitchen Manager',
'note' => 'Keep disposal records'
),

array( 'section' => 'Grease Trap Maintenance',
'task' => 'Grease Trap Maintenance',
'legislation' => 'Environmental Protection Regulations',
'requirement' => 'Clean grease traps regularly',
'frequency' => 'Monthly',
'officer' => 'Compliance Officer',
'note' => 'Prevents environmental fines'
),

array( 'section' => 'Plastic Ban Compliance',
'task' => 'Plastic Ban Compliance',
'legislation' => 'State Laws',
'requirement' => 'Adhere to plastic packaging bans (if applicable)',
'frequency' => 'ong',
'officer' => 'Compliance Officer',
'note' => 'Varies by state'
),

array( 'section' => 'Business Registration',
'task' => 'Business Registration',
'legislation' => 'Corporations Act 2001',
'requirement' => 'Register business with ASIC (ABN, ACN if applicable)',
'frequency' => 'ong',
'officer' => 'Business Owner',
'note' => 'Required before trading'
),

array( 'section' => 'Food Business Registration',
'task' => 'Food Business Registration',
'legislation' => 'State/Territory Food Acts',
'requirement' => 'Register food business with local council',
'frequency' => 'annually',
'officer' => 'Compliance Officer',
'note' => 'Mandatory for all food premises'
),

array( 'section' => 'Trade Waste & Grease Trap Compliance',
'task' => 'Trade Waste & Grease Trap Compliance',
'legislation' => 'Local Water Authorities',
'requirement' => 'Maintain grease traps & dispose of trade waste correctly',
'frequency' => 'ong',
'officer' => 'Facility Manager',
'note' => 'Non-compliance can lead to fines'
),

array( 'section' => 'Fire Safety & Emergency Preparedness',
'task' => 'Fire Safety & Emergency Preparedness',
'legislation' => 'State/Territory Fire Regulations',
'requirement' => 'Conduct fire drills, maintain extinguishers, exits',
'frequency' => 'half yearly',
'officer' => 'Facility Manager',
'note' => 'Keep records for inspections'),
    );
    
    foreach ($arr as $x) {
      
    $compli_id = secure_random_string(10);
                    
                    $task = sanitizePlus($x['task']);
                    $section = sanitizePlus($x["section"]);
                    $legislation = sanitizePlus($x["legislation"]);
                    $requirement = sanitizePlus($x["requirement"]); #required
                    $frequency = sanitizePlus($x["frequency"]);
                    $officers = sanitizePlus($x["officer"]);
                    $note = sanitizePlus($x["note"]);
                    
                    #effectiveness
                    $effectiveness = 'Unassessed';
                    
                    #frequency
                    if (array_key_exists(strtolower($frequency), $allowedFrequencies)){
                        $freq = $allowedFrequencies[strtolower($frequency)];
                    }else{
                        $freq = 7; #as required 
                    }
                    // $section to $task
            
                    if($db->query("INSERT INTO as_compliancedata (module, compliance_id, section, requirement, obligation, controls, status, reference, officers, frequency, effectiveness) 
                                VALUES ('$c_id', '$compli_id', '$task', '$requirement', '$task', null, '$freq', '$legislation', '$officers', '$freq', '$effectiveness')")){
                        echo 'Compliance Data Imported Successfully for: '.$compli_id.'<br>';
                    }else{
                        echo 'Error Importing Compliance Data for: '.$compli_id.'<br>';
                    }
      
    }
    
?>
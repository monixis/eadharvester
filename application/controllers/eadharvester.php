<?php
class eadharvester extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('home_view');
    }

    public function validate()
    {

        // $instituteName = $_POST['institute'];

        $userid = $_POST['gituserId'];
        $repository = $_POST['repository'];
        $branch = $_POST['branch'];
        $agencyCode = $_POST['agencyCode'];
        $addressline1 = $_POST['addressline1'];
        $addressline2 = $_POST['addressline2'];
        $addressline3 = $_POST['addressline3'];
        $addresslineArray=array($addressline1 , $addressline2 , $addressline3);
        //remove white from agencydoe
        $agencyCode= trim($agencyCode);
        $repoName = $_POST['repoName'];
        $file_List = json_decode($_POST['fileList'], true);
        $data["file_list"] = $file_List;
        $num_files = sizeof($file_List);
        $req_id = $this->insert_inst_info($userid, $repository, $branch, $num_files);

        if ($req_id > 0) {
            for ($i = 0; $i < sizeof($file_List); $i++) {
                $rules_valid= array();
                $rules_failed= array();
                $filename = $file_List[$i];
                $path_to_file = "https://raw.githubusercontent.com/" . $userid . "/" . $repository . "/" . $branch . "/" . $filename;
                $xml = simplexml_load_file($path_to_file);


                /* Rule #: Collection Title Validation */

                if ($xml->archdesc->did->unittitle) {
                    $title = $xml->archdesc->did->unittitle;
                    if ($title != null or $title != "") {
                        $rules_valid[] = 1;
                    } else {
                        $rules_failed[] = 1;
                    }
                } else {
                    $rules_failed[] = 1;
                }

                /* Collection Creator Validation  */
                if (isset($xml->archdesc->did->origination)) {
                    $rules_valid[] = 2;
                } else {
                    $rules_failed[] = 2;
                }

                /* Collection Dates Validation*/
                if (isset($xml->archdesc->did->unitdate) || isset($xml->archdesc->did->unittitle->unitdate)) {
                    $rules_valid[] = 3;
                } else {
                    $rules_failed[] = 3;
                }


                /* Abstract Validation */
                if (isset($xml->archdesc->did->abstract)) {
                    $rules_valid[] = 4;
                } else {
                    $rules_failed[] = 4;
                }

                /* Repository Validation */
                if (isset($xml->archdesc->did->repository->corpname)) {
                    $rules_valid[] = 5;
                } else {
                    $rules_failed[] = 5;
                }

                /* Language of Material Validation */
                if (isset($xml->archdesc->did->langmaterial->language) || isset($xml->archdesc->did->langmaterial)) {
                    $rules_valid[] = 6;
                } else {
                    $rules_failed[] = 6;
                }

                /* Physical Description Validation */
                if (isset($xml->archdesc->did->physdesc->extent)) {
                    $rules_valid[] = 7;
                } else {
                    $rules_failed[] = 7;
                }

                /* Access Restrictions Validation */
                if (isset($xml->archdesc->accessrestrict) || isset($xml->archdesc->descgrp->accessrestrict)) {
                    $rules_valid[] = 8;
                } else {
                    $rules_failed[] = 8;
                }
                /* Biography or Historical Note Validation */
                if (isset($xml->archdesc->bioghist)) {
                    $rules_valid[] = 9;
                } else {
                    $rules_failed[] = 9;
                }

                /* Controlled Access Headings Validation */
                if (isset($xml->archdesc->controlaccess)) {
                    $rules_valid[] = 10;
                } else {
                    $rules_failed[] = 10;
                }
                /* Scope and Content Note Validation */

                if (isset($xml->archdesc->scopecontent)) {
                    $rules_valid[] = 11;
                } else {
                    $rules_failed[] = 11;
                }
                /* Use Restrictions Validation */

                if (isset($xml->archdesc->userestrict->p) || isset($xml->archdesc->descgrp->userestrict)) {
                    $rules_valid[] = 12;
                } else {
                    $rules_failed[] = 12;
                }

                /* Agency code Validation */

                /*  if(preg_match("/([\s_\\.\-\(\):])+(.)/", $xml->eadheader->eadid['mainagencycode'])){

                     $rules_failed[] = 13;

                  }else{

                     $rules_valid[] = 13;

                  } */

                if (sizeof($rules_valid)>0) {
                    $rules_valid_to_string = implode(",", $rules_valid);
                } else {
                    $rules_valid_to_string = " ";
                }

                if (sizeof($rules_failed) > 0) {
                    $rules_failed_to_string = implode(",", $rules_failed);
                } else {
                    $rules_failed_to_string = " ";
                    // EAD manipulation
                    $xml->eadheader->eadid['mainagencycode'] = $agencyCode;
                    $xml->archdesc->did->repository->corpname = $repoName;
                    $xml->eadheader->filedesc->publicationstmt->publisher = $repoName;

                    $eadattrs = $xml->attributes();
                    //adding attributes to the ead tag if they are not set
                    if (!isset($eadattrs)) {
                        $xml->addAttribute('xmlns', 'urn:isbn:1-931666-22-9');
                        $xml->addAttribute('xmlns:xsi', 'xsi="http://www.w3.org/2001/XMLSchema-instance');
                        $xml->addAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
                        $xml->addAttribute('xsi:schemaLocation', 'urn:isbn:1-931666-22-9 http://www.loc.gov/ead/ead.xsd');
                    }
                    //remove white space in value of eadid
                    $eadid=$xml->eadheader->eadid;
                    $eadid=trim($eadid);
                    //remove extenion if one is attached to the $eadid
                    $eadid = preg_replace('/\\.[^.\\s]{3,4}$/', '', $eadid);
                    $xml->eadheader->eadid = $eadid;
                    // Download the validated EAD file on the server
                    $fname = basename($path_to_file);
                    $dom = new DOMDocument;
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->loadXML($xml->asXML());
                    //remove old address
                    $list = $dom->getElementsByTagName("addressline");
                    while ($list->length > 0) {
                        $a = $list->item(0);
                        $a->parentNode->removeChild($a);
                    }
                    //Write new address for repo
                    $all_h3s = $dom->getElementsByTagName('address'); // get all h3 tags from the document
                    foreach ($all_h3s as $h3) {
                        $order = $dom->createElement('addressline', $addressline1);
                        $h3->appendChild($order);
                        $order = $dom->createElement('addressline', $addressline2);
                        $h3->appendChild($order);
                        $order = $dom->createElement('addressline', $addressline3);
                        $h3->appendChild($order);
                    }
                    //see if directory exists and create when missing
                    if (!is_dir('validatedFiles/'.$agencyCode)) {
                        mkdir('validatedFiles/'.$agencyCode, 0700);
                    }
                    //save the xml file
                    $dom->save('./validatedFiles/'.$agencyCode.'/'. $fname);
                    //doing a simple replace for a few tags in the xml file
                    $str=file_get_contents('./validatedFiles/'.$agencyCode.'/'. $fname, LIBXML_NOEMPTYTAG);
                    $str=str_replace("<c01", "<c", $str);
                    $str=str_replace("</c01", "</c", $str);
                    $str=str_replace("<c02", "<c", $str);
                    $str=str_replace("</c02", "</c", $str);
                    $str=str_replace("<c03", "<c", $str);
                    $str=str_replace("</c03", "</c", $str);
                    $str=str_replace("<c04", "<c", $str);
                    $str=str_replace("</c04", "</c", $str);
                    $str=str_replace("<c05", "<c", $str);
                    $str=str_replace("</c05", "</c", $str);
                    $str=str_replace("<c06", "<c", $str);
                    $str=str_replace("</c06", "</c", $str);
                    $str=str_replace("<c07", "<c", $str);
                    $str=str_replace("</c07", "</c", $str);
                    $str=str_replace("<c08", "<c", $str);
                    $str=str_replace("</c08", "</c", $str);
                    $str=str_replace("<c09", "<c", $str);
                    $str=str_replace("</c09", "</c", $str);
                    $str=str_replace("<c10", "<c", $str);
                    $str=str_replace("</c10", "</c", $str);
                    file_put_contents('./validatedFiles/'.$agencyCode.'/'. $fname, $str);

                    //This will set a serial for each node for the componets fields
                    $xml2 = simplexml_load_file('./validatedFiles/'.$agencyCode.'/'. $fname);
                    $dom2 = new DOMDocument;
                    $dom2->preserveWhiteSpace = false;
                    $dom2->formatOutput = true;

                    $dom2->loadXML($xml2->asXML());
                    foreach ($dom2->getElementsByTagName('c') as $ctag) {
                              $uniqid= uniqid();
                          $uniqid="c_".$uniqid;
                            $ctag->setAttribute('id', $uniqid);
                    }
                    $dom2->save('./validatedFiles/'.$agencyCode.'/'. $fname);



                    //    $doc->saveXML('./validatedFiles/'.$agencyCode.'/'. $fname);
                }

                $data = array(
                     'req_id'   => $req_id,
                     'file_name'    => $filename,
                     'rules_valid'  => $rules_valid_to_string,
                     'rules_failed' => $rules_failed_to_string
                );

                $this->load->model('eadharvester_model');
                $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');
                if ($_result == 0) {
                    break;
                }
            }
            $validation_array = $this -> eadharvester_model -> getResults($req_id);
            if (sizeof($validation_array)>0) {
                $validation_list = json_encode($validation_array);
                echo $validation_list;
            } else {
                echo "";
            }
        } else {
            echo 0;
        }
    }



    public function getValidationResults()
    {
        $req_id =$this -> input -> get('reqId');

        $this->load->model('eadharvester_model');
        $validation_array = $this -> eadharvester_model -> getResults($req_id);
        if ($validation_array > 0) {
            $validation_list = json_decode(json_encode($validation_array), true);
            return $validation_list;
        } else {
            return "";
        }
    }


    /**
     *
     */
    /*  public function result(){
           $data["repository"] = "dkarnati174";
           $data["branch"] = "master";
           $data["directory"] = "EADs";
           $rules_valid=array(1,2,3, 4, 5, 11, 12);
           $rules_failed=array(6,7,8, 9, 10);
           $rv= implode(",",$rules_valid);
           $rf = implode(",", $rules_failed);
           $file_list=array("1.xml","2.xml","3.xml", "4.xml", "5.xml");

//         foreach ($file_list as $file){
//             $rules_failed=array("6","7","8", "9", "10");
//             $rules_valid=array("1","2","3", "4", "5", "11", "12");
//
//              $rv= implode(",",$rules_valid);
//              $rf = implode(",", $rules_failed);
//
//         }
           $data["file_list"] = $file_list;
           $data["rules_valid"] = $rv;
           $data["rules_failed"] = $rf;
           $this->load->view('results_view', $data);

       }*/


    public function insert_inst_info($gitUserId, $repository, $branch, $num_files)
    {
        date_default_timezone_set('US/Eastern');
        $date = date("m/d/Y");
        $data = array(
       // 'institute_name'   => $instName,
        'git_username'    => $gitUserId,
        'git_repo_name'  => $repository,
        'repo_branch'    => $branch,
        //'branch_dir'    => $directory,
        'num_files'       => $num_files,
    );

        $this->load->model('eadharvester_model');
        $_result = $this->eadharvester_model->insert_institute($data, 'institute_request_info');

        if ($_result > 0) {
            return $_result;
        } else {
            return 0;
        }
    }
    public function insert_val_log($req_id, $file, $rules_valid, $rules_failed)
    {
        $data = array(
            'req_id'   => $req_id,
            'file_name'    => $file,
            'rules_valid'  => $rules_valid,
            'rules_failed'    => $rules_failed
        );

        $this->load->model('eadharvester_model');
        $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');

        if ($_result > 0) {
            return $_result;
        } else {
            return 0;
        }
    }

    public function insert_val_log_test()
    {
        $rules_valid=array(1,2,3, 4, 5, 11, 12);
        $rules_failed=array(6,7,8, 9, 10);
        $rv= implode(",", $rules_valid);
        $rf = implode(",", $rules_failed);

        date_default_timezone_set('US/Eastern');
        $date = date("m/d/Y");
        $data = array(
            'req_id'   => 1,
            'file_name'    => "test.xml",
            'rules_valid'  => $rv,
            'rules_failed'    => $rf,

        );

        $this->load->model('eadharvester_model');
        $_result = $this->eadharvester_model->insert_val_log($data, 'request_val_log');

        if ($_result > 0) {
            echo $_result;
        } else {
            echo "failed";
        }
    }


    public function insert_user_info()
    {
        $this->load->model('eadharvester_model');

        $data = array(
        'institute_name'   => "dkarnati",
        'git_username'    => "dkarnati174",
        'git_repo_name'  => "EADs",
        'repo_branch'    => "master",
        'branch_dir'    =>"test",
        "num_files" => 2
    );

        $_result = $this->eadharvester_model->insert_institute($data, 'institute_request_info');

        if ($_result > 0) {
            echo $_result;
        } else {
            echo "failed";
        }
    }
}

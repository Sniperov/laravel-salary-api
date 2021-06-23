<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Tax;
use App\Http\Requests\SalaryRequest;

class SalaryController extends Controller
{
    public function getCalculatedSalary(SalaryRequest $request)
    {
        $data = $request->validated();
        $invalidGroup = array_key_exists('invalid_group',$data) ? $data['invalid_group'] : null;

        return response()->json(
            $this->calculateTaxes(
                $data['salary'], 
                $data['tax_ms'], 
                $data['is_pensioner'], 
                $data['is_invalid'],
                $invalidGroup
            )
        );
    }

    public function storeCalculatedSalary(SalaryRequest $request)
    {
        $data = $request->validated();
        $invalidGroup = array_key_exists('invalid_group',$data) ? $data['invalid_group'] : null;
        $calculated = $this->calculateTaxes($data['salary'], $data['tax_ms'], $data['is_pensioner'], $data['is_invalid'], $invalidGroup);

        $data['salary'] = $calculated['finalSalary'];
        $salary = Salary::create($data);
        
        $taxes = $calculated['taxes'];
        $taxes['salary_id'] = $salary->id;

        $tax = Tax::create($taxes);

        return response()->json($calculated);
    }

    private function calculateTaxes($salary, $taxMS, $isPensioner, $isInvalid, $invalidGroup)
    {
        $cshcci = $salary * Tax::CSHCCI;
        $cpc = $salary * Tax::CPC;
        $cshi = $salary * Tax::CSHI;
        $ssc = ($salary - $cpc) * Tax::SSC;
        $iit = $this->calculateIIT($salary, $taxMS, $cpc, $cshcci);

        if ($isPensioner == false && $isInvalid == false) {
            $finalSalary = $salary - $cshcci - $cpc - $cshi - $ssc - $iit;

            return [
                'taxes' => [
                    'cshcci' => $cshcci,
                    'cpc' => $cpc,
                    'cshi' => $cshi,
                    'ssc' => $ssc,
                    'iit' => $iit,
                ],
                'finalSalary' => $finalSalary,
            ];
        }
        elseif ($isPensioner == true && $isInvalid == false) {
            $finalSalary = $salary - $iit;

            return [
                'taxes' => [
                    'iit' => $iit,
                ],
                'finalSalary' => $finalSalary,
            ];
        }
        elseif ($isPensioner == true && $isInvalid == true) {
            return [
                'taxes' => null,
                'finalSalary' => $salary,
            ];
        }
        elseif ($isPensioner == false && $isInvalid == true && ($invalidGroup == 1 || $invalidGroup == 2)) {
            if ($salary / Tax::MCI > 882) {
                $finalSalary = $salary - $ssc - $iit;

                return [
                    'taxes' => [
                        'ssc' => $ssc,
                        'iit' => $iit,
                    ],
                    'finalSalary' => $finalSalary,
                ];
            }else{
                $finalSalary = $salary - $ssc;

                return [
                    'taxes' => [
                        'ssc' => $ssc,
                    ],
                    'finalSalary' => $finalSalary,
                ];
            }
        }
        elseif ($isPensioner == false && $isInvalid == true && $invalidGroup == 3) {
            if ($salary / Tax::MCI > 882) {
                $finalSalary = $salary - $ssc - $iit - $cpc;

                return [
                    'taxes' => [
                        'ssc' => $ssc,
                        'cpc' => $cpc,
                        'iit' => $iit,
                    ],
                    'finalSalary' => $finalSalary,
                ];
            }else{
                $finalSalary = $salary - $ssc - $cpc;

                return [
                    'taxes' => [
                        'ssc' => $ssc,
                        'cpc' => $cpc,
                    ],
                    'finalSalary' => $finalSalary,
                ];
            }
        }


    }

    private function calculateIIT($salary, $taxMS, $cpc, $cshcci)
    {
        if ($salary / Tax::MCI < 25 && $taxMS == false) {
            $iit = ($salary - $cpc - $cshcci -( ($salary - $cpc - $cshcci) *0.9 ) ) * 0.1;
        }elseif ($salary / Tax::MCI > 25 && $taxMS == false) {
            $iit = ($salary - $cpc - $cshcci) * 0.1;
        }elseif ($salary / Tax::MCI < 25 && $taxMS == true){
            $iit = ($salary - $cpc - Tax::MS - $cshcci -( ($salary - $cpc - Tax::MS - $cshcci) *0.9 ) ) * 0.1;
        }else{
            $iit = ($salary - $cpc - Tax::MS - $cshcci) * 0.1;
        }

        return $iit;
    }
}

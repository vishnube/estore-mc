<?php defined('BASEPATH') or exit('No direct script access allowed');
/**


 * CodeIgniter PDF Library
 *
 * Generate PDF's in CodeIgniter applications using dompdf 
 *
 * @source          https://github.com/dompdf/dompdf
 * @author          Shihabu Rahman K

    Dompdf 1.0.2
    
    WARNING: Make sure you have commented out all print, echo functions in your controller method, else you might get an error saying something similar to "the pdf file is damaged".

 */

require_once APPPATH . 'third_party/dompdf_1-0-2/autoload.inc.php';

use Dompdf\Dompdf;

class Pdf extends DOMPDF
{
    protected $CI;
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
    }

    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access  public
     * @param   string  $view The view to load
     * @param   array   $data The view data
     * @return  void
     */
    public function load_view($html, $file_name)
    {
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($file_name);
    }
}

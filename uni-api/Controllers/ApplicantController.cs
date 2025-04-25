using api.Structures;
using Microsoft.AspNetCore.Mvc;

namespace api.Controllers
{
    [ApiController]
    [Route("Applicants")]
    public class ApplicantController : ControllerBase
    {
        private Logic logic;
        public ApplicantController(Logic logic)
        {
            this.logic = logic;
        }

        [HttpPost("/Add")]
        public IActionResult AddApplicant([FromBody] ApplicantStruct applicant)
        {
            //logic.AddApplicant(applicant);
            return Ok();
        }
    }
}


using Microsoft.AspNetCore.Mvc;
using System.Text.Json;

namespace api.Controllers
{
    [ApiController]
    [Route("Lists")]
    public class ListsController : ControllerBase
    {
        private Logic logic;
        public ListsController(Logic logic)
        {
            this.logic = logic;
        }

        [HttpGet("{direction}/list")]
        public IActionResult GetDirectionList(string direction)
        {
            return logic.GetDirectionList(direction);
        }

        [HttpGet("{direction}/originals")]
        public IActionResult GetDirectionOriginals(string direction)
        {
            return logic.GetDirectionOriginals(direction);
        }

        [HttpGet("{direction}/result")]
        public IActionResult GetDirectionResult(string direction)
        {
            return logic.GetDirectionFinalList(direction);
        }

        [HttpGet("directions")]
        public IActionResult GetAllDirections()
        {
            return logic.GetAllDirections();
        }

    }
}


using System.Threading.Tasks;

using Microsoft.AspNetCore.Mvc;

namespace TodoApi.Controllers
{

[Route("api/[controller]")]
    public class BlogController : ControllerBase
    {
        public BlogController(Connector.AppDb db)
        {
            Db = db;
        }




      // GET api/blog/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetOne(int id)
        {
            await Db.Connection.OpenAsync();
            var query = new AlbumQuery(Db);
            var result = await query.FindOneAsync(id);
            if (result is null)
                return new NotFoundResult();
            return new OkObjectResult(result);
        }


      public Connector.AppDb Db { get; }
}

}



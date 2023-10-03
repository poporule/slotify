using System.Data;
using System.Threading.Tasks;
using MySqlConnector;



namespace TodoApi
{
    public class Album
    {
        public int Id {get;set;}
        public string Title {get;set;}
        public int Artist {get;set;}
        public int Genre {get;set;}
        public string ArtworkPath {get;set;}


    }

}
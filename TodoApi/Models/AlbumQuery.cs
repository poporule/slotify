using System.Collections.Generic;
using System.Data;
using System.Data.Common;
using System.Threading.Tasks;
using MySqlConnector;


namespace TodoApi
{
    public class AlbumQuery
    {


        public TodoApi.Connector.AppDb Db { get; }

        public AlbumQuery(TodoApi.Connector.AppDb db)
        {
            Db = db;
            
        }

        public async Task<Album> FindOneAsync(int id)
        {
            using var cmd = Db.Connection.CreateCommand();
            cmd.CommandText = @"SELECT `id`, `title`, `artist`, `genre`, `artworkPath` FROM `albums` WHERE `id` = @id";
            cmd.Parameters.Add(new MySqlParameter
            {
                ParameterName = "@id",
                DbType = DbType.Int32,
                Value = id,
            });
            var result = await cmd.ExecuteReaderAsync();
            return (Album) result[0]; 
        }

    }

}
namespace api.Structures
{
    public class ApplicantInsertionStruct
    {
        public string Snils { get; set; }       // numeric(11)
        public string PhoneNumber { get; set; } // numeric(11)
        public string Email { get; set; }       // varchar(100)
        public int AchievementBall { get; set; } // numeric(2)
        public bool IsNeedDormitory { get; set; } // boolean
        public int IsSubmitOriginal { get; set; } // numeric(1)
    }
}

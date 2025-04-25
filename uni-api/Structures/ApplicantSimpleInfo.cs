namespace api.Structures
{
    public class ApplicantSimpleInfo
    {
        public string Snils { get; set; }
        public bool IsSubmitOriginal { get; set; } // numeric(1) интерпретируем как bool
    }
}

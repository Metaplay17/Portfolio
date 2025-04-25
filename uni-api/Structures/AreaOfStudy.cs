namespace api.Structures
{
    public class AreaOfStudy
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public string Code { get; set; }
        public int BudgetPlaces { get; set; }
        public int FeePlaces { get; set; }
        public string FormEducation { get; set; }
        public int Subject1Id { get; set; }
        public int Subject2Id { get; set; }
        public int Subject3Id { get; set; }
        public int? Subject4Id { get; set; } // Nullable, так как столбец может содержать NULL
        public int? Subject5Id { get; set; }
        public int? Subject6Id { get; set; }
    }
}

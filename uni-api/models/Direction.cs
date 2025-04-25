namespace api.models
{
    public class Direction
    {
        private string name;
        private string code;
        private SortedSet<Applicant> applicants;
        private List<Applicant> finalList;
        private int freePlaces;
        private int feePlaces;

        public Direction(string name, string code, int freePlaces, int feePlaces)
        {
            this.name = name;
            this.code = code;
            applicants = new SortedSet<Applicant>();
            finalList = new List<Applicant>();
            this.freePlaces = freePlaces;
            this.freePlaces = freePlaces;
        }

        public void AddApplicant(Applicant applicant)
        {
            applicants.Add(applicant);
        }

        public Applicant[] Applicants
        {
            get { return applicants.ToArray(); }
        }

        public string Name
        {
            get { return name; }
        }

        public int FreePlaces
        {
            get { return freePlaces; }
        }

        public int FeePlaces
        {
            get { return feePlaces; }
        }

        public Applicant[] GetList()
        {
            return applicants.ToArray();
        }

        public Applicant[] GetOriginalsList()
        {
            List<Applicant> result = new List<Applicant>();
            foreach (Applicant applicant in applicants)
            {
                if (applicant.IsSubmitOriginalDocs)
                {
                    result.Add(applicant);
                }
            }
            result.Reverse();
            return result.ToArray();
        }

        public List<Applicant> FinalList
        {
            get { return finalList; }
        }
    }
}

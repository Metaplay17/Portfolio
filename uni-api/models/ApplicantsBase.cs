namespace api.models
{
    public class ApplicantsBase
    {
        private Database database;
        private Dictionary<string, Direction> directions;

        public ApplicantsBase()
        {
            database = new Database();
            directions = new Dictionary<string, Direction>();
        }

        private void LoadApplicants()
        {
            Applicant[] applicants = database.LoadApplicants();
            foreach (Applicant applicant in applicants)
            {
                foreach(string priority in applicant.Priorities)
                {
                    directions[priority].AddApplicant(applicant);
                }
            }
        }

        private void LoadDirections()
        {
            directions = database.LoadDirections();
            
        }

        public void Init()
        {
            LoadDirections();
            LoadApplicants();
        }

        private void AddDirection(Direction direction)
        {
            directions.Add(direction.Name, direction);
        }

        public Applicant[] GetDirectionList(string directionName)
        {
            return directions[directionName].GetList();
        }

        public Applicant[] GetDirectionOriginals(string directionName)
        {
            return directions[directionName].GetOriginalsList();
        }

        public Direction[] DirectionsArray
        {
            get { return directions.Values.ToArray(); }
        }

        public Dictionary<string, Direction> DirectionsDictionary
        {
            get { return directions; }
        }
    }
}

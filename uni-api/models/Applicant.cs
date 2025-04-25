namespace api.models
{
    public class Applicant : IComparable<Applicant>
    {
        private Dictionary<string, int> results;
        private int ballAmount;
        private bool isSubmitOriginalDocs;
        private string snils;
        private string[] priorities;

        public Applicant(string snils)
        {
            results = new Dictionary<string, int>();
            this.snils = snils;
            priorities = new string[5];
        }

        public void AddResult(string subject, int ball)
        {
            results.Add(subject, ball);
            ballAmount += ball;
        }

        public void RemoveResult(string subject) 
        { 
            if (results.ContainsKey(subject))
            {
                ballAmount -= results[subject];
                results.Remove(subject);
            }
        }

        public void SetPrioroties(string[] newPrioroties)
        {
            priorities = newPrioroties;
        }

        public void ConfirmOrginalDocs()
        {
            isSubmitOriginalDocs = true;
        }

        public void RemoveConfirmationOriginalDocs()
        {
            isSubmitOriginalDocs = false;
        }

        public string Snils
        {
            get { return snils; }
        }

        public int BallAmount
        {
            get { return ballAmount; }
        }

        public bool IsSubmitOriginalDocs
        {
            get { return isSubmitOriginalDocs; }
        }

        public int CompareTo(Applicant other)
        {
            return ballAmount - other.ballAmount;
        }

        public string[] Priorities
        {
            get { return priorities; }
        }
    }
}

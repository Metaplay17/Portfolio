namespace api.models
{
    public class Subject
    {
        private int id;
        private string name;
        private int threshold;

        public Subject(int id, string name, int threshold)
        {
            this.id = id;
            this.name = name;
            this.threshold = threshold;
        }

        public int Id
        {
            get { return id; }
        }

        public string Name
        {
            get { return name; }
        }

        public int Threshold
        {
            get { return threshold; }
        }

    }
}

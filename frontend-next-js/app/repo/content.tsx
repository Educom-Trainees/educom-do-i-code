export default function Content({ children }: { children: React.ReactNode }) {

    return (
      <div className="
      mt-2 mr-4 mb-2 ml-4
      sm:mt-4 sm:mr-8 sm:mb-4 sm:ml-8
      md:mt-8 md:mr-16 md:mb-8 md:ml-16
      lg:mt-16 lg:mr-32 lg:mb-16 lg:ml-32">
        {children}
      </div>
  
    );
  }